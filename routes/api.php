<?php

use Illuminate\Support\Facades\Route;

Route::get('ping', function () {
    return response()->json([
        'message' => 'pong',
    ]);
});


// group prefix with /web3
Route::prefix('web3')->group(function () {
});
