<?php

use App\Http\Controllers\Api\Tasks;
use App\Http\Controllers\Api\Titles;
use App\Http\Controllers\Api\Users;
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


Route::post('/user', [Users::class, 'store']);

Route::post('/login', [Users::class, 'login']);

Route::post('/check_email', [Users::class, 'checkEmail']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/titles', [Titles::class, 'all']);
    Route::get('/titles/{id}', [Titles::class, 'get']);
    Route::post('/titles', [Titles::class, 'store']);
    Route::put('/titles/{id}', [Titles::class, 'put']);
    Route::delete('/titles/{id}', [Titles::class, 'delete']);

    Route::get('/task', [Tasks::class, 'all']);
    Route::get('/task/{id}', [Tasks::class, 'get']);
    Route::post('/task', [Tasks::class, 'store']);
    Route::put('/task/{id}', [Tasks::class, 'put']);
    Route::delete('/task/{id}', [Tasks::class, 'delete']);

    Route::get('/user', [Users::class, 'get']);
    Route::put('/user', [Users::class, 'put']);
    Route::delete('/user', [Users::class, 'delete']);
});