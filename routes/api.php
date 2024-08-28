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
    Route::middleware('guest')->group(function () {
        // Login route
        Route::post('/login', [App\Http\Controllers\V1\AuthenticationController::class, 'login']);

        // Register route
        Route::post('/register', [App\Http\Controllers\V1\AuthenticationController::class, 'register']);
    });

    Route::middleware('auth:sanctum')->group(function (){
        // Find application
        Route::get('/seller-application', [App\Http\Controllers\V1\SellerApplicationController::class, 'findUserApplication']);

        Route::middleware('role:customer')->group(function () {
            // Seller application related routes
            Route::post('/seller-application/new', [App\Http\Controllers\V1\SellerApplicationController::class, 'newSellerApplication']);
            
        });
    
        Route::middleware('role:admin')->group(function () {
            // Seller applications route
            Route::get('/fetch-seller-applications', [App\Http\Controllers\V1\SellerApplicationController::class, 'fetchApplications']);
            Route::post('/seller-application/reject', [App\Http\Controllers\V1\SellerApplicationController::class, 'rejectApplication']);
            Route::post('/seller-application/verify', [App\Http\Controllers\V1\SellerApplicationController::class, 'verifyApplication']);
        });
    });
    

    Route::get('/user', [App\Http\Controllers\V1\UserController::class, 'index'])->middleware(['auth:sanctum']);
    Route::get('/user/{user:id}', [App\Http\Controllers\V1\UserController::class, 'show'])->middleware(['auth:sanctum']);
    
    Route::post('/logout', [App\Http\Controllers\V1\AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});