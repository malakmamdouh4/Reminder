<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//

Route::middleware("api.lang")->group(function () {

    Route::group(['prefix' => 'user'], function () {
        Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
        Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
        Route::post('patient_info', [\App\Http\Controllers\Api\AuthController::class, 'patientInfo']);
    });

    Route::post('/password/forget', [\App\Http\Controllers\Api\AuthController::class, 'forgetPassword']);
    Route::post('/password/reset-activation', [\App\Http\Controllers\Api\AuthController::class, 'resetPasswordActivation']);
    Route::post('/password/reset', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword']);

    Route::get('/home', [\App\Http\Controllers\Api\HomeController::class, 'home']);
    Route::get('/countries', [\App\Http\Controllers\Api\HomeController::class, 'countries']);

    Route::middleware('auth:api')->group( function () {
        Route::middleware('api.active')->group(function () {

          Route::post('add-medical-history', [\App\Http\Controllers\Api\HistoryController::class, 'addMedicalHistory']);
          Route::post('update-medical-history', [\App\Http\Controllers\Api\HistoryController::class, 'updateMedicalHistory']);
          Route::get('get-history', [\App\Http\Controllers\Api\HistoryController::class, 'getHistory']);

        });
    });


});

