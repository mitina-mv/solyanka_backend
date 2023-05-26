<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\RoleController;
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

Route::group(['middleware' => 'web'], function() {
    // CRUD chats
    Route::group(['prefix' => 'chat'], function() {
        Route::get('/', [ChatController::class, 'list']);
        Route::post('/create', [ChatController::class, 'create']);
        Route::get('/{id}', [ChatController::class, 'read']);
        // Route::post('/{id}', [ChatController::class, 'update']);
        Route::delete('/{id}', [ChatController::class, 'destroy']);
    });
    // CRUD roles
    Route::group(['prefix' => 'role'], function() {
        Route::get('/', [RoleController::class, 'list']);
        Route::get('/{id}', [RoleController::class, 'read']);

        // доступен для авторизованных пользователей
        Route::group(['middleware' => 'auth'], function() {
            Route::post('/create', [RoleController::class, 'create']);
            Route::delete('/{id}', [RoleController::class, 'destroy']);
        });
    });

});

