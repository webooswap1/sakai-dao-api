<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidatorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'address'        => $this->address,
            'powers'         => '',
            'amount'         => $this->balance,
            'ref_commission' => '',
            'accumulation'   => '',
            'last_stake_date'=> $this->last_stake_date,
            'apr'            => '100%',
            'status_vote'    => 'Yes',
        ];
    }
}
