<?php

use App\Http\Controllers\CustomController;
use App\Http\Controllers\MerchantAuth\AuthenticatedSessionController;
use App\Http\Controllers\MerchantAuth\EmailVerificationNotificationController;
use App\Http\Controllers\MerchantAuth\EmailVerificationPromptController;
use App\Http\Controllers\MerchantAuth\RegisteredUserController;
use App\Http\Controllers\MerchantAuth\VerifyEmailController;
use App\Http\Controllers\PasswordLessController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:merchant')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
        if(config('verify.way') == 'passwordless'){
            Route::post('login', [PasswordLessController::class, 'store']);
            Route::get('verify-email/{merchant}', [PasswordLessController::class, 'verify'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('login.verify');

        }else{
            Route::post('login', [AuthenticatedSessionController::class, 'store']);
        }

});

Route::middleware('merchant')->group(function () {
    if(config('verify.way') == 'email'){
        Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    }
    

    // CVT

    if(config('verify.way') == 'cvt'){
        Route::get('verify-email', [CustomController::class , 'notice'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{token}', [CustomController::class , 'verify'])
        ->middleware([ 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [CustomController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    }





    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
});

