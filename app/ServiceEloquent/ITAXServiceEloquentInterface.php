<?php
namespace App\ServiceEloquent;

interface ITAXServiceEloquentInterface {
    public function shill(
        string $address,
        string $url,
        int $epoch
    );

    public function claimReward(
        string $address,
        int $epoch
    );

    public function stake(
        string $address,
        int $amount,
        string $txHash,
        int $epoch
    );

    public function unstake(
        string $address,
        int $amount,
        string $txHash,
        int $epoch
    );
}
