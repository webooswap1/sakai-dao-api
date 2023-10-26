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



}
