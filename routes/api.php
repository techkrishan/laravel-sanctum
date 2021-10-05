<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\{AuthenticationController, UserController, QuestionController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1'], function () {

    // Unauthenticated routes  
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::post('/verification-email', [AuthenticationController::class, 'sendVerificationEmail']);
    Route::post('/verify-email', [AuthenticationController::class, 'verifyEmail']);
    Route::post('/reset-password-otp', [AuthenticationController::class, 'resetPasswordOtp']);
    Route::post('/reset-password', [AuthenticationController::class, 'resetPassword']);

    // Authenticated routes
    Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

        Route::post('/logout', [AuthenticationController::class, 'logout']);
        
        // Verified routes
        Route::group(['middleware' => ['verified']], function () {
            Route::resource('questions', QuestionController::class);
            Route::resource('users', UserController::class);
        });

    });

});


