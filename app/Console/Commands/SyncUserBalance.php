<?php

namespace App\Console\Commands;

use App\Models\Abi;
use App\Models\ConfigAddress;
use App\Models\Stake;
use App\Models\UserBalance;
use App\Utils\Web3Helper;
use Illuminate\Console\Command;

class SyncUserBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-user-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize user balance with web3';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $abi = Abi::where('code','ERC20')->first();
        $address = ConfigAddress::where('code','TOKEN_DAO')->first();

        $userBalances = Stake::select('stakes.address')->groupBy('address')->get();
        foreach ($userBalances as $user){
            $response = Web3Helper::callFunction(
                $address->address,
                'balanceOf',
                [
                    $user->address
                ],
                $abi->abi
            );

            if(isset($response['result'])) {
                UserBalance::updateOrCreate([
                    'address' => $user->address
                ], [
                    'balance' => $response['result']
                ]);
            }
        }


    }
}
