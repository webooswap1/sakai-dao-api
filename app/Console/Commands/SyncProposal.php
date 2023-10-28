<?php

namespace App\Console\Commands;

use App\Models\Abi;
use App\Models\ConfigAddress;
use App\Models\Proposal;
use App\Models\Stake;
use App\Utils\Web3Helper;
use Illuminate\Console\Command;

class SyncProposal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-proposal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize proposals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $abi = Abi::where('code','SAKAIPROPOSAL')->first();
        $address = ConfigAddress::where('code','PROPOSAL')->first();

        $proposals = Proposal::whereIn('status',[
            'pending',
            'published',
        ])->get();
        foreach ($proposals as $proposal){
            $response = Web3Helper::callFunction(
                $address->address,
                'proposals',
                [$proposal->id],
                $abi->abi
            );

            $responseTotalVotedParticipant = Web3Helper::callFunction(
                $address->address,
                'getTotalParticipantVoted',
                [$proposal->id],
                $abi->abi
            );
            $proposal->admin_vote_approve = $response['result']['adminApproved'];
            $proposal->admin_vote_reject = $response['result']['adminRejected'];
            $proposal->user_vote_approve = $response['result']['approvePower'];
            $proposal->user_vote_reject = $response['result']['rejectPower'];
            $proposal->total_participant = $responseTotalVotedParticipant['result'];

            if($response['result']['status'] === '0'){
                $proposal->status = 'pending';
            } else if($response['result']['status'] === '1'){
                $proposal->status = 'published';
            } else if($response['result']['status'] === '2'){
                $proposal->status = 'rejected';
            } else if($response['result']['status'] === '3'){
                $proposal->status = 'canceled';
            } else if($response['result']['status'] === '4'){
                $proposal->status = 'finished';
            }
            $proposal->save();
        }
    }
}
