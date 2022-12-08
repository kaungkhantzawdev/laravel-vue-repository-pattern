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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('v1')->controller(\App\Http\Controllers\BlogController::class)->group(function(){

    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{slug}', 'show');
    Route::delete('/{id}', 'destroy');
    Route::delete('/photo-delete/{id}', 'deletePhoto');
    Route::put('/{id}', 'update');
//    Route::apiResource('/', \App\Http\Controllers\BlogController::class);

});
