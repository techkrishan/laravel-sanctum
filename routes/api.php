<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AuthenticationController;

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


// User registration  
Route::post('/register', [AuthenticationController::class, 'register']);
// User Login
Route::post('/login', [AuthenticationController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [AuthenticationController::class, 'logout']);
    
    Route::resource('questions', QuestionController::class);

});
