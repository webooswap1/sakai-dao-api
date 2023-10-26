<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
use App\Http\Requests\StakeUnstakeRequest;
use App\Http\Resources\ProposalResource;
use App\Http\Resources\ValidatorResource;
use App\Models\Stake;
use App\Models\UserBalance;
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

        return $this->response([
            'amount_in_wei' => '0'
        ]);
    }

    public function getRewardFromStake()
    {
        return $this->response([
            'amount_in_wei' => '0'
        ]);
    }
}
