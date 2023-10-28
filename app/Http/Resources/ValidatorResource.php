<?php

namespace App\Http\Resources;

use App\Models\Config;
use App\Models\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PHP\Math\BigNumber\BigNumber;

class ValidatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $config = Config::first();
        $totalSupplyInWei = new BigNumber($config->total_supply_in_wei);
        $balance = new BigNumber($this->balance);
        $percentage = $balance->divide($totalSupplyInWei)->multiply(100);

        $isCanVote =  \Brick\Math\BigNumber::of($this->balance)->isGreaterThan($config->minimum_vote_in_wei);
        return [
            'address'        => $this->address,
            'powers'         => $percentage->setScale(2)->getValue() . '%',
            'amount'         => $this->balance,
            'ref_commission' => '',
            'accumulation'   => '',
            'last_stake_date'=> $this->last_stake_date,
            'apr'            => '100%',
            'status_vote'    => $isCanVote,
        ];
    }
}
