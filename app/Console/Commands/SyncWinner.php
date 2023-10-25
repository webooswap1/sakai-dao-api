<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-winner {--chainId=} {--protocol=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chainId = $this->option('chainId');
        $protocol = $this->option('protocol');
        Log::info('Sync Winner Start At'.date('Y-m-d H:i:s'));
        $http = Http::get("http://web3js:3000/check-winners?chain={$chainId}&protocol={$protocol}");
        $response = $http->json();
        Log::info("Sync Winner Response: ".json_encode($response));
        if(isset($response['winnersInfo'])){
            foreach ($response['winnersInfo'] as $item){
                Transaction::where([
                    'chain_id' => $chainId,
                    'token_id' => $item['tokenId'],
                ])->update([
                    'amount_reward' => $item['amountReward'],
                ]);
            }
        }
        Log::info('Sync Winner End End At'.date('Y-m-d H:i:s'));
    }
}
