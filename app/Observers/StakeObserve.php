<?php

namespace App\Observers;

use App\Enums\StakeTypeEnum;
use App\Models\Stake;
use App\Models\UserBalance;
use PHP\Math\BigNumber\BigNumber;

class StakeObserve
{
    /**
     * Handle the Stake "created" event.
     */
    public function created(Stake $stake): void
    {
        $userBalance = UserBalance::where('address', $stake->address)->first();
        if($stake->type === StakeTypeEnum::STAKE->value){
            if(!$userBalance){
                $userBalance = new UserBalance();
                $userBalance->address = $stake->address;
                $userBalance->balance = $stake->amount;
                $userBalance->save();
            } else {
                $currentBalance = new BigNumber($userBalance->balance);
                $amountStake = new BigNumber($stake->amount);
                $userBalance->balance = $currentBalance->add($amountStake)->getValue();
                $userBalance->save();
            }
        } else {
            if(!$userBalance){
                $userBalance = new UserBalance();
                $userBalance->address = $stake->address;
                $userBalance->balance = 0;
                $userBalance->save();
            } else {
                $currentBalance = new BigNumber($userBalance->balance);
                $amountStake = new BigNumber($stake->amount);
                $userBalance->balance = $currentBalance->subtract($amountStake)->getValue();
                $userBalance->save();
            }
        }

    }

    /**
     * Handle the Stake "updated" event.
     */
    public function updated(Stake $stake): void
    {
        //
    }

    /**
     * Handle the Stake "deleted" event.
     */
    public function deleted(Stake $stake): void
    {
        //
    }

    /**
     * Handle the Stake "restored" event.
     */
    public function restored(Stake $stake): void
    {
        //
    }

    /**
     * Handle the Stake "force deleted" event.
     */
    public function forceDeleted(Stake $stake): void
    {
        //
    }
}
