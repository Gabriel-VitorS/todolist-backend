<?php

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


Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/titles', [Titles::class, 'get']);
    Route::post('/titles', [Titles::class, 'store']);
    Route::put('/titles/{id}', [Titles::class, 'put']);
});