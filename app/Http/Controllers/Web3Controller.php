<?php

namespace App\Http\Controllers;

use App\Models\Shill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class Web3Controller extends Controller
{
    private string $web3Url;
    private string $rpcUrl;
    private string $adminKey;

    public function __construct()
    {
        $config = DB::table('configs')->first();
        $this->rpcUrl = $config->rpc_url;
        $this->web3Url = env('WEB3_URL');
    }

    public function ping()
    {
        $response = Http::get($this->web3Url.'/ping');
        return response()->json([
            'message' => $response->json()['message'],
        ]);
    }



}
