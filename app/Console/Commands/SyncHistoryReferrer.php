<?php

namespace App\Console\Commands;

use App\Models\Abi;
use App\Models\ConfigAddress;
use App\Models\ReferrerRewardHistory;
use App\Models\Stake;
use App\Models\StakeRewardHistory;
use App\Utils\Web3Helper;
use Illuminate\Console\Command;
use PHP\Math\BigNumber\BigNumber;

class SyncHistoryReferrer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-history-referrer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'syncronize referrer history';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $abi = Abi::where('code','SAKAIDAO')->first();
        $address = ConfigAddress::where('code','TOKEN_DAO')->first();

        $userBalances = Stake::select('stakes.referrer')->groupBy('referrer')->get();
        foreach ($userBalances as $user){
            $response = Web3Helper::callFunction(
                $address->address,
                'rewardsFromReferrer',
                [$user->referrer],
                $abi->abi
            );
            if($response['result']=== '0') {
                continue;
            }

            // get stake history latest data
            $latestHistory = ReferrerRewardHistory::where('address',$user->referrer)->orderBy('created_at','desc')->first();
            if(!$latestHistory || (string)$latestHistory->last_amount !== (string)$response['result']){

                $amount = $response['result'];
                if($latestHistory) {
                    $lastAmount = new BigNumber($latestHistory->last_amount);
                    $currentAmount = new BigNumber($response['result']);
                    $diffAmount = $currentAmount->subtract($lastAmount);
                    $amount = $diffAmount->setScale(0)->getValue();
                }

                $latestHistory = new ReferrerRewardHistory();
                $latestHistory->address = $user->referrer;
                $latestHistory->amount = $amount;
                $latestHistory->last_amount = $response['result'];
                $latestHistory->save();
            }

        }
    }
}
