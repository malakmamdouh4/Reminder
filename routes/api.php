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
    });
    Route::post('user_login', [\App\Http\Controllers\Api\AuthController::class, 'userLogin']);
    
    Route::post('/password/forget', [\App\Http\Controllers\Api\AuthController::class, 'forgetPassword']);
    Route::post('/password/reset-activation', [\App\Http\Controllers\Api\AuthController::class, 'resetPasswordActivation']);
    Route::post('/password/reset', [\App\Http\Controllers\Api\AuthController::class, 'resetPassword']);
    
    Route::get('/home', [\App\Http\Controllers\Api\HomeController::class, 'home']);
    Route::get('/countries', [\App\Http\Controllers\Api\HomeController::class, 'countries']);
    
    Route::middleware('auth:api')->group( function () {
        Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        
        // Route::middleware('api.active')->group(function () {
            
          Route::post('add-medical-history', [\App\Http\Controllers\Api\HistoryController::class, 'addMedicalHistory']);
          Route::post('update-medical-history', [\App\Http\Controllers\Api\HistoryController::class, 'updateMedicalHistory']);
          Route::get('get-history', [\App\Http\Controllers\Api\HistoryController::class, 'getHistory']);

          Route::post('add-task', [\App\Http\Controllers\Api\TaskController::class, 'addTask']);
          Route::get('get-tasks', [\App\Http\Controllers\Api\TaskController::class, 'getTasks']);
          Route::post('complete-task', [\App\Http\Controllers\Api\TaskController::class, 'completeTask']);

          Route::post('add-reminder', [\App\Http\Controllers\Api\ReminderController::class, 'addReminder']);
          Route::get('get-reminders', [\App\Http\Controllers\Api\ReminderController::class, 'getReminders']);
          Route::post('update-reminder', [\App\Http\Controllers\Api\ReminderController::class, 'updateReminder']);
          Route::post('delete-reminder', [\App\Http\Controllers\Api\ReminderController::class, 'deleteReminder']);

          Route::post('add-track', [\App\Http\Controllers\Api\TrackController::class, 'addTrack']);
          Route::get('get-tracks', [\App\Http\Controllers\Api\TrackController::class, 'getTracks']);

          Route::post('add-memory', [\App\Http\Controllers\Api\MemoryController::class, 'addMemory']);
          Route::get('get-memories', [\App\Http\Controllers\Api\MemoryController::class, 'getMemories']);
          Route::get('get-memory', [\App\Http\Controllers\Api\MemoryController::class, 'getMemory']);
          
          Route::post('update-location', [\App\Http\Controllers\Api\HomeController::class, 'updateLocation']);
          Route::get('get-location', [\App\Http\Controllers\Api\HomeController::class, 'getLocation']);

          Route::get('/notifications',[\App\Http\Controllers\Api\HomeController::class,'notifications']);
          Route::get('/unseen-notifications-count',[\App\Http\Controllers\Api\HomeController::class,'unseenNotificationsCount']);  
       
          Route::get('/get-exam',[\App\Http\Controllers\Api\ExamController::class,'getExam']);
       
        // });
    });


});

