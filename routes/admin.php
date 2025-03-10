<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\DeliveryChargesController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SiteSettingController;
use App\Http\Controllers\Api\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {

    Route::get('user/{id}', [UserController::class, 'show']);
    Route::post('change-password', [UserController::class, 'changePassword']);
    Route::put('update-profile-image/{id}', [UserController::class, 'profileImageUpdate']);

    Route::get('dashboard', [DashboardController::class, 'index']);

    
    Route::get('site-settings', [SiteSettingController::class, 'index']);
    Route::post('site-settings', [SiteSettingController::class, 'store']);

    Route::get('category', [CategoryController::class, 'index']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::post('category', [CategoryController::class, 'store']);

    Route::get('brand', [BrandController::class, 'index']);
    Route::delete('brand/{id}', [BrandController::class, 'destroy']);
    Route::put('brand/{id}', [BrandController::class, 'update']);
    Route::post('brand', [BrandController::class, 'store']);

    Route::get('product', [ProductController::class, 'index']);
    Route::get('product/{slug}', [ProductController::class, 'show']);
    Route::post('product', [ProductController::class, 'store']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);

    Route::get('delivery-charges', [DeliveryChargesController::class, 'index']);
    Route::match(['put', 'patch'], 'delivery-charges/bulk-update', [DeliveryChargesController::class, 'bulkUpdate']);

    Route::get('customers', [CustomerController::class, 'index']);
    Route::get('customer-orders/{customer_id}', [CustomerController::class, 'customerOrders']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::put('orders/update-status/{id}', [OrderController::class, 'updateStatus']);



});