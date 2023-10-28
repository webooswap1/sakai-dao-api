<?php
namespace App\Utils;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Web3Helper {
    public static function callFunction(
        string $contractAddress,
        string $functionName,
        array $functionArgs,
        $abi
    )
    {
        $config = DB::table('configs')->first();
        $payload = [
            'contract_address' => $contractAddress,
            'rpc_url'          => $config->rpc_url,
            'function_name'    => $functionName,
            'function_args'    => $functionArgs,
            'abi'              => $abi,
        ];
        $response = Http::timeout(120)->post(env('WEB3_URL').'/callfunction',$payload);
        return $response->json();
    }
}
