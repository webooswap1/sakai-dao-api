<?php

namespace App\Http\Controllers;

use App\Models\Shill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Web3Controller extends Controller
{
    private string $web3Url;
    private string $rpcUrl;
    private string $adminKey;

    public function __construct()
    {
        $this->web3Url = env('WEB3_URL');
        $this->rpcUrl = env('WEB3_RPC');
        $this->adminKey = env('WEB3_ADMIN_KEY');
    }

    public function ping()
    {
        $response = Http::get($this->web3Url.'/ping');
        return response()->json([
            'message' => $response->json()['message'],
        ]);
    }

    public function checkGas(Request $request)
    {
        $request->validate([
            'address'      => 'required',
            'stakeAddress' => 'required',
        ]);
        $payload = [
            'address'      => $request->address,
            'stakeAddress' => $request->stakeAddress,
            'rpcUrl'       => $this->rpcUrl,
            'adminKey'     => $this->adminKey,
        ];
        $response = Http::post($this->web3Url.'/check-gas', $payload);
        return response()->json($response->json());
    }

    public function claimReward(Request $request)
    {
        $request->validate([
            'address'      => 'required',
            'stakeAddress' => 'required',
            'gasHash'      => 'required',
        ]);
        $shill = Shill::where('address', $request->address)
            ->whereNull('claim_reward_hash')
            ->whereNull('claim_gas_hash')
            ->first();
        if(!$shill) {
            return response()->json([
                'message' => 'No shill found',
            ]);
        }
        $shill->claim_gas_hash = $request->input('gasHash');
        $payload = [
            'address'      => $request->input('address'),
            'stakeAddress' => $request->input('stakeAddress'),
            'rpcUrl'       => $this->rpcUrl,
            'adminKey'     => $this->adminKey,
        ];
        $response = Http::post($this->web3Url.'/claim-reward', $payload);
        $shill->claim_reward_hash = $response->json()['transactionHash'];
        $shill->save();
        return response()->json($response->json());
    }

    public function canClaim(Request $request){
        $shill = Shill::where('address', $request->address)
            ->whereNull('claim_reward_hash')
            ->whereNull('claim_gas_hash')
            ->first();
            return response()->json([
                'status' => !$shill ? false : true,
            ]);
    }

}
