<?php
namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Web3Helper {
    public static function callFunction(
        string $contractAddress,
        string $functionName,
        array $functionArgs,
        string $abi
    )
    {
        $response = Http::post(env('WEB3_URL').'/callfunction',[
            'contract_address' => $contractAddress,
            'rpc_url'          => env('WEB3_RPC'),
            'function_name'    => $functionName,
            'function_args'    => $functionArgs,
            'abi'              => json_decode($abi),
        ]);
        return $response->json();
    }
}
