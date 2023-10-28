<?php

namespace App\Console\Commands;

use App\Models\Abi;
use App\Models\Config;
use App\Models\ConfigAddress;
use App\Utils\Web3Helper;
use Illuminate\Console\Command;

class SyncConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize config';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $abi = Abi::where('code','SAKAIDAO')->first();
        $address = ConfigAddress::where('code','TOKEN_DAO')->first();
        $config = Config::first();
        $responseDao = Web3Helper::callFunction(
            $address->address,
            'totalSupply',
            [],
            $abi->abi
        );

        $responseMinimumVote = Web3Helper::callFunction(
            $address->address,
            'minimumStakeForVote',
            [],
            $abi->abi
        );
        if(isset($responseDao['result'])) {
            $config->total_supply_in_wei = $responseDao['result'];
            $config->minimum_vote_in_wei = $responseMinimumVote['result'];
            $config->save();
        }
    }
}
