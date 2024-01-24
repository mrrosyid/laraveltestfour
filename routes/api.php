<?php

use App\Http\Controllers\EarlyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAdminMiddleware;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/EarlyRegister', [EarlyController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware(ApiAdminMiddleware::class)->group(function(){
    Route::get('/User', [UserController::class, 'get']);
    Route::put('/User/{id}', [UserController::class, 'update']);
    Route::delete('/User/{id}', [UserController::class, 'delete']);

    Route::get("/EarlyAccess", [EarlyController::class, 'get']);
    Route::put('/EarlyAccess/{id}', [EarlyController::class, 'update']);
    Route::delete('/EarlyAccess/{id}', [EarlyController::class, 'delete']);
});

Route::middleware(ApiAuthMiddleware::class)->group(function(){
    Route::delete('/Logout', [UserController::class, 'logout']);
});
