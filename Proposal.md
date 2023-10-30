# Proposal.

1. User can write proposal using, title and description as a params.
```solidity
 function _createProposal(string memory _title, string memory _description) internal {
        /** this function will create proposal */

        proposalId+=1;
        Proposal storage newProposal = proposals[proposalId];
        newProposal.id = proposalId;
        newProposal.owner = msg.sender;
        newProposal.title = _title;
        newProposal.description = _description;
        newProposal.status = ProposalStatus.PENDING;
        newProposal.timestamp = block.timestamp;
        _activateProposal();
        emit CreateProposal(proposalId, _title, _description, block.timestamp);
    }
```
2. After that, proposal ID will be incremented by 1 and default status is PENDING.
3. Then, Admin which registered on default will be able to approve or reject the proposal via DAPPS.
```solidity
constructor(address _tokenVoteAddress, address[9] memory _admins) MultiAdmin(_admins) {
        tokenVoteAddress = _tokenVoteAddress;
        proposalAdminTreshold = 5;
        userVoteDuration = 3 days;
    }
```
4. Duration voting is default 3 days, it should be same with epoch period on SakaiDAO (for prevent user which stake on current epoch, approve on same epoch).
5. If, voting is filled by 5 admins, then proposal will be approved. or rejection if filled by 5 admins.
```solidity
function _voteAdmin(uint256 _proposalId, bool _isApproved) internal {
        /** this function will vote admin */

        proposals[_proposalId].adminsVoted[msg.sender] = true;
        proposals[_proposalId].admins.push(msg.sender);

        if(_isApproved) {
            proposals[_proposalId].adminApproved++;
            proposals[_proposalId].adminsApproved[msg.sender] = true;
        } else {
            proposals[_proposalId].adminRejected++;
            proposals[_proposalId].adminsRejected[msg.sender] = true;
        }

        // Check if proposal reached admin treshold, if yes, then publish proposal, if no, then reject proposal
        if(proposals[_proposalId].adminApproved >= proposalAdminTreshold && proposals[_proposalId].status == ProposalStatus.PENDING) {
            proposals[_proposalId].status = ProposalStatus.PUBLISHED;
            proposals[_proposalId].userVoteStartTimestamp = block.timestamp;
            proposals[_proposalId].userVoteEndTimestamp = block.timestamp + userVoteDuration;
        } else if(proposals[_proposalId].adminRejected >= proposalAdminTreshold && proposals[_proposalId].status == ProposalStatus.PENDING) {
            proposals[_proposalId].status = ProposalStatus.REJECTED;
        }
        _activateProposal();
        emit AdminVoteProposal(_proposalId, msg.sender, _isApproved, block.timestamp);
    }
```
6. If there is no pending proposal, should activate current proposal, and if there active proposal is expired, should be finished.
```solidity
function _activateProposal() internal {
        /** this function will activate proposal if there is no active proposal or active proposal is finished */
        if (activeProposalId == 0 || proposals[activeProposalId].status == ProposalStatus.FINISHED) {
            for (uint256 i = activeProposalId + 1; i <= proposalId; i++) {
                if (proposals[i].status == ProposalStatus.PENDING) {
                    activeProposalId = i;
                    break;
                }
            }
        }
    }
```
7. After active proposal, user can vote the proposal.
```solidity
function _voteUser(uint256 _proposalId, bool _isApproved) internal {
        /** this function will vote user */

        proposals[_proposalId].votersVoted[msg.sender] = true;
        proposals[_proposalId].voters.push(msg.sender);

        if(_isApproved) {
            proposals[_proposalId].votersApproved[msg.sender] = true;
            proposals[_proposalId].votersApprovePower[msg.sender] += IERC20(tokenVoteAddress).balanceOf(msg.sender);
            proposals[_proposalId].approvePower += IERC20(tokenVoteAddress).balanceOf(msg.sender);
        } else {
            proposals[_proposalId].votersRejected[msg.sender] = true;
            proposals[_proposalId].votersRejectPower[msg.sender] += IERC20(tokenVoteAddress).balanceOf(msg.sender);
            proposals[_proposalId].rejectPower += IERC20(tokenVoteAddress).balanceOf(msg.sender);
        }

        // finish vote if user vote is ended
        if(proposals[_proposalId].userVoteEndTimestamp <= block.timestamp) {
            proposals[_proposalId].status = ProposalStatus.FINISHED;
        }

        _activateProposal();
        emit UserVoteProposal(_proposalId, msg.sender, _isApproved, block.timestamp);
    }
```
8. We store some data on proposal, such as:
```solidity
struct Proposal {
        uint256 id;
        address owner;
        string title;
        string description;
        uint256 votes;
        ProposalStatus status; // 0: pending, 1: published, 2: rejected, 3: canceled, 4: finished
        uint256 adminApproved;
        uint256 adminRejected;
        uint256 timestamp;
        uint256 minimumEpochForVoting;
        uint256 userVoteStartTimestamp;
        uint256 userVoteEndTimestamp;
        uint256 approvePower;
        uint256 rejectPower;

        address[] admins;
        mapping(address => bool) adminsVoted;
        mapping(address => bool) adminsApproved;
        mapping(address => bool) adminsRejected;

        address[] voters;
        mapping(address => bool) votersVoted;
        mapping(address => bool) votersApproved;
        mapping(address => bool) votersRejected;
        mapping(address => uint256) votersApprovePower;
        mapping(address => uint256) votersRejectPower;
    }
```

9. Thast's all, thank you.
