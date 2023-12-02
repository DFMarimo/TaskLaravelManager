<?php

use App\Http\Controllers\UserTaskController;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::controller('App\Http\Controllers\Authentication\AuthController')->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/user', 'user')->name('user');
    /* forget password routes */
    Route::post('/forget-password', 'forgetPassword')->name('forget-password');
    Route::post('/reset-password', 'resetPassword')->name('password.reset');

    /*
     * TODO google login and register
     *
     * callback from google
     * Route::post('http://protodo.com/google', 'google');
     * */
});

Route::apiResource('expertises', \App\Http\Controllers\api\ExpertiseController::class);
Route::apiResource('tasks', \App\Http\Controllers\api\TaskController::class);

Route::apiResource('results', \App\Http\Controllers\api\ResultController::class);
Route::post('/results/change-status' , [\App\Http\Controllers\api\ResultController::class ,'changeStatus']);

Route::apiResource('users.tasks' , UserTaskController::class)->only(['show','index']);
