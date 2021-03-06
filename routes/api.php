<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\SubtypeController;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/auth/tokens', 'store');
}); 

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{id}', 'show');
    Route::post('/users', 'store');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(CardController::class)->group(function () {
        Route::get('/cards', 'index');
        Route::get('/cards/{id}', 'show');
        Route::post('/cards', 'store');
        Route::patch('/cards/{id}', 'update');
        Route::delete('/cards/{id}', 'destroy');
    });

    Route::controller(ImageController::class)->group(function () {
        Route::get('/images', 'index');
        Route::get('/images/{id}', 'show');
        Route::post('/images', 'store');
        Route::patch('/images/{id}', 'update');
        Route::delete('/images/{id}', 'destroy');
    });

    Route::controller(SubtypeController::class)->group(function () {
        Route::get('/subtypes', 'index');
        Route::get('/subtypes/{id}', 'show');
        Route::post('/subtypes', 'store');
        Route::put('/subtypes/{id}', 'update');
    });
});
