<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\V1\CartController;
use App\Http\Controllers\V1\ProductController;

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
        Route::post('/login', [App\Http\Controllers\V1\AuthenticationController::class, 'login'])
        ->name('login');

        // Register route
        Route::post('/register', [App\Http\Controllers\V1\AuthenticationController::class, 'register']);
    });

    Route::middleware('auth:sanctum')->group(function (){
        // Find application
        Route::get('/seller-application', [App\Http\Controllers\V1\SellerApplicationController::class, 'findUserApplication']);

        // Business related routes
        // Route to view business
        Route::get('/business/{business:slug}', [App\Http\Controllers\V1\BusinessController::class, 'businessMainPage']);

        // View cart
        Route::get('/cart', [App\Http\Controllers\V1\CartController::class, 'viewCart']);
        // Update cart quantity
        Route::post('/cart', [App\Http\Controllers\V1\CartController::class, 'updateQuantity']);
        // Delete cart
        Route::post('/cart/delete-product', [App\Http\Controllers\V1\CartController::class, 'deleteProduct']);
        // Checkout Cart
        Route::post('/cart/checkout', [App\Http\Controllers\V1\TransactionController::class, 'checkout']);
        // View transaction
        Route::get('/transactions', [App\Http\Controllers\V1\TransactionController::class, 'viewTransactions']);
        // update transaction status (completed, canceled)
        Route::post('/transactions/update-status', [App\Http\Controllers\V1\TransactionController::class, 'updateTransactionStatus']);
        // Add review
        Route::post('/transactions/add-review', [App\Http\Controllers\V1\ReviewController::class, 'addReview']);

        // Product routes
        Route::prefix('/product')->middleware('auth')->group(function(){
            // view product detail
            Route::get('/{product:slug}', [App\Http\Controllers\V1\ProductController::class, 'customerProductDetail']);

            // add product to cart
            Route::post('/{product:slug}', [App\Http\Controllers\V1\CartController::class, 'addProduct']);
        });
        
        Route::middleware('role:customer')->group(function () {
            // Seller application related routes
            Route::post('/seller-application/new', [App\Http\Controllers\V1\SellerApplicationController::class, 'newSellerApplication']);
            
        });
    
        // Seller dashboard routes
        Route::prefix('/seller/dashboard')->middleware('role:seller')->group(function () {
            // Get business products
            Route::get('/products', [App\Http\Controllers\V1\ProductController::class, 'viewProducts']);
            Route::post('/products', [App\Http\Controllers\V1\ProductController::class, 'storeProduct']);

            Route::middleware('check.business.owner')->group(function() {
                // Business product details
                Route::get('/products/{product:slug}', [App\Http\Controllers\V1\ProductController::class, 'viewProductDetail']);
                Route::post('/products/{product:slug}/add-stock', [App\Http\Controllers\V1\ProductController::class, 'addStock']);
                Route::post('/products/{product:slug}/set-discount', [App\Http\Controllers\V1\ProductController::class, 'setDiscount']);
                Route::post('/products/{product:slug}/edit', [App\Http\Controllers\V1\ProductController::class, 'updateProduct']);

                // Reply to review
                Route::post('/{review:id}/reply', [App\Http\Controllers\V1\ReviewController::class, 'addReply'])
                ->whereNumber('review');
            });

            // Check transaction busienss owner
            route::middleware('check.transaction.business.owner')->group(function (){
                // get transaction
                Route::get('/transactions/fetch-transactions', [App\Http\Controllers\V1\TransactionController::class, 'fetchTransactions']);

                // get transaction detail
                Route::get('/transactions/{transaction:id}', [App\Http\Controllers\V1\TransactionController::class, 'viewTransactionDetail'])
                ->whereNumber('transaction');

                // Update transaction status
                Route::post('/transactions/update-status', [App\Http\Controllers\V1\TransactionController::class, 'updateTransactionStatus']);
            });
        });

        // Admin dashboard routes
        Route::prefix('/admin/dashboard')->middleware('role:admin')->group(function () {
            // Seller applications route
            Route::get('/fetch-seller-applications', [App\Http\Controllers\V1\SellerApplicationController::class, 'fetchApplications']);
            Route::post('/seller-application/reject', [App\Http\Controllers\V1\SellerApplicationController::class, 'rejectApplication']);
            Route::post('/seller-application/verify', [App\Http\Controllers\V1\SellerApplicationController::class, 'verifyApplication']);

            // Category routes
            Route::get('/category', [App\Http\Controllers\V1\CategoryController::class, 'index']);
            Route::post('/category', [App\Http\Controllers\V1\CategoryController::class, 'store']);
            Route::get('/category/{category:id}', [App\Http\Controllers\V1\CategoryController::class, 'show']);
            Route::post('/category/update', [App\Http\Controllers\V1\CategoryController::class, 'update']);
            Route::post('/category/delete', [App\Http\Controllers\V1\CategoryController::class, 'destroy']);

            // Business routes
            Route::get('/business', [App\Http\Controllers\V1\BusinessController::class, 'index']);
            Route::get('/business/{business:slug}', [App\Http\Controllers\V1\BusinessController::class, 'show']);
        });
    });
    

    Route::get('/user', [App\Http\Controllers\V1\UserController::class, 'index'])->middleware(['auth:sanctum']);
    Route::get('/user/{user:id}', [App\Http\Controllers\V1\UserController::class, 'show'])->middleware(['auth:sanctum']);
    
    Route::post('/logout', [App\Http\Controllers\V1\AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});