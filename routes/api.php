<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;

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

// v1 api 
Route::prefix('v1')->group(function () {
    Route::get('/user', [App\Http\Controllers\V1\UserController::class, 'index'])->middleware(['auth:sanctum']);
    Route::get('/user/{user:id}', [App\Http\Controllers\V1\UserController::class, 'show'])->middleware(['auth:sanctum']);
    
    Route::post('/login', [App\Http\Controllers\V1\AuthenticationController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\V1\AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});