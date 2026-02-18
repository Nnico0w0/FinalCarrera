<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\AuthController;

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

/*
|--------------------------------------------------------------------------
| API Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('api.auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
    
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('api.auth.logout');
        Route::post('refresh', [AuthController::class, 'refresh'])->name('api.auth.refresh');
        Route::get('me', [AuthController::class, 'me'])->name('api.auth.me');
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    // Users endpoints
    Route::apiResource('users', UserController::class);
    
    // Products endpoints
    Route::apiResource('products', ProductController::class);
    
    // Categories endpoints
    Route::apiResource('categories', CategoryController::class);
    
    // Brands endpoints
    Route::apiResource('brands', BrandController::class);
    
    // Orders endpoints
    Route::apiResource('orders', OrderController::class);
});
