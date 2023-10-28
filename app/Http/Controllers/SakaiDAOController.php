<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
use App\Http\Requests\StakeUnstakeRequest;
use App\Http\Resources\ProposalResource;
use App\Http\Resources\ReferralRewardHistoryResource;
use App\Http\Resources\StakeRewardHistoryResource;
use App\Http\Resources\ValidatorResource;
use App\Models\Abi;
use App\Models\ConfigAddress;
use App\Models\ReferrerRewardHistory;
use App\Models\Stake;
use App\Models\StakeRewardHistory;
use App\Models\UserBalance;
use App\Utils\Web3Helper;
use Illuminate\Http\Request;

class SakaiDAOController extends Controller
{
    public function stake(StakeUnstakeRequest $request): \Illuminate\Http\JsonResponse
    {
        $model = new \App\Models\Stake();
        $model->fill($request->validated());
        $model->save();
        return $this->response($model);
    }

    public function getValidators(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = UserBalance::query();

        return $this->response(ValidatorResource::collection($model->paginate(10)));
    }

    public function createProposal(ProposalRequest $request): \Illuminate\Http\JsonResponse
    {
        $model = new \App\Models\Proposal();
        $model->fill($request->validated());
        $model->save();
        return $this->response($model);
    }

    public function getProposals(Request $request): \Illuminate\Http\JsonResponse
    {
        $model = \App\Models\Proposal::query();
        $model->orderBy('created_at', 'desc');

        return $this->response(ProposalResource::collection($model->paginate(10)));
    }

    public function getRewardFromReferrer(Request $request): \Illuminate\Http\JsonResponse
    {
        $abi = Abi::where('code','SAKAIDAO')->first();
        $address = ConfigAddress::where('code','TOKEN_DAO')->first();
        $response = Web3Helper::callFunction(
            $address->address,
            'rewardsFromReferrer',
            [$request->address],
            $abi->abi
        );
        $amount = 0;
        if(isset($response['result'])){
            $amount = $response['result'];
        }
        return $this->response([
            'amount_in_wei' => $amount
        ]);
    }

    public function getRewardFromStake(Request $request)
    {
        $abi = Abi::where('code','SAKAIDAO')->first();
        $address = ConfigAddress::where('code','TOKEN_DAO')->first();
        $response = Web3Helper::callFunction(
            $address->address,
            'shares',
            [$request->address],
            $abi->abi
        );
        $amount = 0;
        if(isset($response['result']) && isset($response['result']['totalClaimed'])){
            $amount = $response['result']['totalClaimed'];
        }
        $amountUnclaimed = 0;
        $response = Web3Helper::callFunction(
            $address->address,
            'dividendOf',
            [$request->address],
            $abi->abi
        );

        if(isset($response['result'])){
            $amountUnclaimed = $response['result'];
        }
        return $this->response([
            'claimed_amount_in_wei' => $amount,
            'unclaimed_amount_in_wei' => $amountUnclaimed
        ]);
    }

    public function getStakeRewardHistory($address, Request $request)
    {
        $model = StakeRewardHistory::query();
        $model->where('address',$address);
        $model->orderBy('created_at','desc');
        return StakeRewardHistoryResource::collection($model->paginate($request->input('limit',10)));
    }

    public function getReferralRewardHistory($address, Request $request)
    {
        $model = ReferrerRewardHistory::query();
        $model->where('address',$address);
        $model->orderBy('created_at','desc');
        return ReferralRewardHistoryResource::collection($model->paginate($request->input('limit',10)));
    }
}
