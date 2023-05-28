<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleFavouritesController;
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


// для запросов неавторизованных пользователей
Route::group(['prefix' => 'request'], function() {
    Route::post('/', [RequestController::class, 'create']);
});

Route::group(['middleware' => 'web'], function() {
    // CRUD chats
    Route::group(['prefix' => 'chat', 'middleware' => 'auth'], function() {
        Route::get('/', [ChatController::class, 'list']);
        Route::post('/create', [ChatController::class, 'create']);
        Route::get('/{id}', [ChatController::class, 'read']);
        // Route::post('/{id}', [ChatController::class, 'update']);
        Route::delete('/{id}', [ChatController::class, 'destroy']);
    });

    // CRUD roles
    Route::group(['prefix' => 'role', 'middleware' => 'auth'], function() {
        Route::get('/', [RoleController::class, 'list']);
        Route::get('/{id}', [RoleController::class, 'read']);
        Route::post('/create', [RoleController::class, 'create']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    // добавление роли в избранное 
    Route::group(['prefix' => 'user-role', 'middleware' => 'auth'], function() {
        Route::get('/', [RoleFavouritesController::class, 'list']);

        Route::post('/{id}', [RoleFavouritesController::class, 'create']);
        Route::delete('/{id}', [RoleFavouritesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'request', 'middleware' => 'auth'], function() {
        Route::post('/{chat_id}', [RequestController::class, 'create']);
    });

    Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function() {
        Route::get('/{id}', [UserController::class, 'read']);
        Route::post('/{id}', [UserController::class, 'update']);
        Route::post('/{id}/magicAvatar', [UserController::class, 'magicAvatar']);
    });

        // Route::post('/{id}', [RoleFavouritesController::class, 'create']);
        // Route::delete('/{id}', [RoleFavouritesController::class, 'destroy']);
    // });
});

