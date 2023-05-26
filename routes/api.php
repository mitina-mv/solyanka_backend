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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'chat'], function() {
    Route::get('/', [ChatController::class, 'list']);
    Route::get('/create', [ChatController::class, 'create']);
    Route::get('/{id}', [ChatController::class, 'read']);
    // Route::post('/{id}', [ChatController::class, 'update']);
    Route::delete('/{id}', [ChatController::class, 'destroy']);
});
