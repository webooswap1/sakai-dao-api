<?php

use Illuminate\Support\Facades\Route;

Route::get('ping',[\App\Http\Controllers\UtilsController::class,'ping']);
Route::get('time',[\App\Http\Controllers\UtilsController::class,'time']);
Route::post('stake',[\App\Http\Controllers\SakaiDAOController::class,'stake']);
Route::get('validators',[\App\Http\Controllers\SakaiDAOController::class,'getValidators']);
Route::post('proposal',[\App\Http\Controllers\SakaiDAOController::class,'createProposal']);
Route::get('proposal',[\App\Http\Controllers\SakaiDAOController::class,'getProposals']);
Route::get('rewardFromReferrer',[\App\Http\Controllers\SakaiDAOController::class,'getRewardFromReferrer']);
Route::get('rewardFromStake',[\App\Http\Controllers\SakaiDAOController::class,'getRewardFromStake']);

// group prefix with /web3
Route::prefix('web3')->group(function () {
});
