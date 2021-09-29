<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\QuestionController;
use App\Http\Controllers\V1\AuthenticationController;

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

    // Authenticated routes
    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::post('/logout', [AuthenticationController::class, 'logout']);
    
        Route::resource('questions', QuestionController::class);

    });

});


