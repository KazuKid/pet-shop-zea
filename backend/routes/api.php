<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes – Zea Pet Shop
|--------------------------------------------------------------------------
*/

// ── Public: Products ──────────────────────────────────────────────────────
Route::get('/products',        [ProductController::class, 'index']);
Route::get('/products/{id}',   [ProductController::class, 'show']);

// ── Public: Categories ───────────────────────────────────────────────────
Route::get('/categories',                   [CategoryController::class, 'index']);
Route::get('/categories/{slug}/products',   [CategoryController::class, 'products']);

// ── Public: Orders (guest checkout + tracking) ───────────────────────────
Route::post('/orders',                    [OrderController::class, 'store']);
Route::get('/orders/{orderNumber}',       [OrderController::class, 'show']);

// ── Auth ──────────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user',    [AuthController::class, 'user']);
    });
});

// Profile (authenticated users)
Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::get('/',       [ProfileController::class, 'show']);
    Route::put('/',       [ProfileController::class, 'update']);
    Route::get('/orders', [ProfileController::class, 'orders']);
});

// ── Admin (authenticated) ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Dashboard stats
    Route::get('/stats', [AdminController::class, 'stats']);

    // Products CRUD
    Route::post('/products',        [ProductController::class, 'store']);
    Route::put('/products/{id}',    [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // Categories CRUD
    Route::post('/categories',        [CategoryController::class, 'store']);
    Route::put('/categories/{id}',    [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Orders management
    Route::get('/orders',               [OrderController::class, 'index']);
    Route::put('/orders/{id}/status',   [OrderController::class, 'updateStatus']);

    // Users management
    Route::get('/users',         [UserController::class, 'index']);
    Route::get('/users/{id}',    [UserController::class, 'show']);
    Route::put('/users/{id}',    [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
});
