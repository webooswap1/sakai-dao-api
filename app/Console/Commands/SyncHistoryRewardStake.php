<?php

namespace App\Console\Commands;

use App\Models\Abi;
use App\Models\ConfigAddress;
use App\Models\Stake;
use App\Models\StakeRewardHistory;
use App\Utils\Web3Helper;
use Illuminate\Console\Command;
use PHP\Math\BigNumber\BigNumber;

class SyncHistoryRewardStake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-history-reward-stake';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sync history reward from stake';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $abi = Abi::where('code','SAKAIDAO')->first();
        $address = ConfigAddress::where('code','TOKEN_DAO')->first();

        $userBalances = Stake::select('stakes.address')->groupBy('address')->get();
        foreach ($userBalances as $user){
            $response = Web3Helper::callFunction(
                $address->address,
                'shares',
                [$user->address],
                $abi->abi
            );
            if($response['result']['totalClaimed'] === '0') {
                continue;
            }

            // get stake history latest data
            $latestHistory = StakeRewardHistory::where('address',$user->address)->orderBy('created_at','desc')->first();
            if(!$latestHistory || (int)$latestHistory->last_timestamp < (int)$response['result']['lastWithdrawnTimestamp']){
                $amount = $response['result']['totalClaimed'];
                if($latestHistory) {
                    $lastAmount = new BigNumber($latestHistory->accumulated_amount);
                    $currentAmount = new BigNumber($response['result']['totalClaimed']);
                    $diffAmount = $currentAmount->subtract($lastAmount);
                    $amount = $diffAmount->setScale(0)->getValue();
                }
                $latestHistory = new StakeRewardHistory();
                $latestHistory->address = $user->address;
                $latestHistory->accumulated_amount = $response['result']['totalClaimed'];
                $latestHistory->amount = $amount;
                $latestHistory->last_timestamp = $response['result']['lastWithdrawnTimestamp'];
                $latestHistory->save();
            }
        }
    }
}
