<?php
namespace App\ServiceEloquent;

use App\Enums\StakeTypeEnum;
use App\Models\Shill;
use App\Models\Stake;
use Illuminate\Http\JsonResponse;

class ITAXServiceEloquent implements ITAXServiceEloquentInterface
{
    public function shill(string $address, string $url, int $epoch): JsonResponse
    {
        $shill = Shill::updateOrCreate([
          'address' => $address,
          'epoch' => $epoch,
        ],[
            'url' => $url,
        ]);
        return response()->json([
            'message'   => 'Shill created successfully.',
            'shill'     => $shill,
        ]);
    }

    public function claimReward(string $address, int $epoch) {

    }

    public function stake(string $address, int $amount, string $txHash, int $epoch): JsonResponse
    {
        $stake = Stake::create([
            'type' => StakeTypeEnum::STAKE->value,
            'address' => $address,
            'amount' => $amount,
            'txHash' => $txHash,
            'epoch' => $epoch,
        ]);
        return response()->json([
            'message'   => 'Stake created successfully.',
            'stake'     => $stake,
        ]);
    }

    public function unstake(string $address, int $amount, string $txHash, int $epoch): JsonResponse
    {
        $stake = Stake::create([
            'type' => StakeTypeEnum::UNSTAKE->value,
            'address' => $address,
            'amount' => $amount,
            'txHash' => $txHash,
            'epoch' => $epoch,
        ]);
        return response()->json([
            'message'   => 'Unstake created successfully.',
            'stake'     => $stake,
        ]);
    }
}
