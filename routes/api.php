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
Route::post('profile',[\App\Http\Controllers\UtilsController::class,'updateProfile']);
Route::delete('profile',[\App\Http\Controllers\UtilsController::class,'deleteProfile']);
Route::get('sync',[\App\Http\Controllers\UtilsController::class,'syncWeb3']);
Route::get('stake-reward-history/{address}',[\App\Http\Controllers\SakaiDAOController::class,'getStakeRewardHistory']);
Route::get('referral-reward-history/{address}',[\App\Http\Controllers\SakaiDAOController::class,'getReferralRewardHistory']);
// group prefix with /web3
Route::prefix('web3')->group(function () {
    Route::get('ping',[\App\Http\Controllers\Web3Controller::class,'ping']);
});

// Admin Area
Route::post('/config',[\App\Http\Controllers\UtilsController::class,'updateConfig']);
