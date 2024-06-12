<?php

use App\Http\Controllers\Admin\Application\ApplicationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\RegisterController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\SendVerificationCodeController;
use App\Http\Controllers\Admin\Auth\VerifyCodeController;
use App\Http\Controllers\Admin\Job\JobController;
use App\Http\Controllers\Admin\Profile\ProfileController;

Route::post('register', RegisterController::class);
Route::post('login', LoginController::class);
Route::post('send-verification-code', SendVerificationCodeController::class);
Route::post('verify-code', VerifyCodeController::class);


Route::group(['middleware' => ['auth:admin', 'admin_middleware']], function () {
    Route::get('profile', ProfileController::class);

    Route::apiResource('jobs', JobController::class);
    Route::apiResource('applications', ApplicationController::class);
});
