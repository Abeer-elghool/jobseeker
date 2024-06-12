<?php

use App\Http\Controllers\User\Application\ApplicationController;
use App\Http\Controllers\User\Job\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\Auth\SendVerificationCodeController;
use App\Http\Controllers\User\Auth\VerifyCodeController;
use App\Http\Controllers\User\Profile\ProfileController;

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);
Route::post('send-verification-code', SendVerificationCodeController::class);
Route::post('verify-code', VerifyCodeController::class);


Route::group(['middleware' => ['auth:user', 'user_middleware']], function () {
    Route::get('profile', ProfileController::class);

    Route::apiResource('jobs', JobController::class);
    Route::post('jobs/apply', ApplicationController::class);
});
