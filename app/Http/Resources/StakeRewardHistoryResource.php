<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PHP\Math\BigNumber\BigNumber;

class StakeRewardHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $amountInWei = new BigNumber($this->amount);
        $accumulatedAmountInWei = new BigNumber($this->accumulated_amount);

        $amountInEther = $amountInWei->divide(new BigNumber(10**18))->toString();
        $accumulatedAmountInEther = $accumulatedAmountInWei->divide(new BigNumber(10**18))->toString();
        return [
            'address'                     => $this->address,
            'amount_in_wei'               => (string) $this->amount,
            'amount_in_ether'             => (double) $amountInEther,
            'accumulated_amount_in_wei'   => (string) $this->accumulated_amount,
            'accumulated_amount_in_ether' => (double) $accumulatedAmountInEther,
            'timestamp'                   => $this->created_at->timestamp,
        ];
    }
}
