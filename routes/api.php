<?php

use App\Http\Controllers\Api\Admin\SiteSettingController;
use App\Http\Controllers\Api\Customer\AuthController;
use App\Http\Controllers\Api\Customer\BrandController;
use App\Http\Controllers\Api\Customer\CartController;
use App\Http\Controllers\Api\Customer\CategoryController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\Customer\OrderController;
use App\Http\Controllers\Api\Customer\PaymentOptionController;
use App\Http\Controllers\Api\Customer\ProductController;
use App\Http\Controllers\Api\Customer\ProductSwapController;
use App\Http\Controllers\Api\Customer\ReviewController;
use App\Http\Controllers\Api\Customer\ShippingDetailController;
use App\Http\Controllers\Api\Customer\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




Route::post('login', [AuthController::class, 'login']);
Route::post('signup', [CustomerController::class, 'store']);
Route::get('site-settings', [SiteSettingController::class, 'index']);
Route::get('category', [CategoryController::class, 'index']);
Route::get('category/{slug}', [CategoryController::class, 'show']);
Route::get('brand', [BrandController::class, 'index']);

Route::get('products', [ProductController::class, 'index']);


$middleware = Auth::guard('api')->user() ? ['auth:api'] : [];
Route::get('brand/{slug}', [BrandController::class, 'show'])->middleware($middleware);
Route::get('products/{slug}', [ProductController::class, 'show'])->middleware($middleware);



Route::middleware(['auth:api'])->group(function () {

    Route::put('customer/{id}', [CustomerController::class, 'update']);
    Route::get('customer/{id}', [CustomerController::class, 'show']);
    Route::put('customer/update-profile-image/{id}', [CustomerController::class, 'profileImageUpdate']);
    Route::post('customer/change-password', [CustomerController::class, 'changePassword']);

    Route::get('wishlists', [WishlistController::class, 'index']);
    Route::post('wishlists', [WishlistController::class, 'store']);
    Route::delete('wishlists/{id}', [WishlistController::class, 'destroy']);

    Route::get('carts', [CartController::class, 'index']);
    Route::post('carts', [CartController::class, 'store']);
    Route::put('carts/quantity-update/{id}', [CartController::class, 'cartQuantityUpdate']);
    Route::delete('carts/{id}', [CartController::class, 'destroy']);


    Route::get('shipping-details', [ShippingDetailController::class, 'index']);
    Route::get('shipping-details/{id}', [ShippingDetailController::class, 'show']);
    Route::put('shipping-details/{id}', [ShippingDetailController::class, 'update']);
    Route::post('shipping-details', [ShippingDetailController::class, 'store']);
    Route::put('shipping-details/update-status/{id}', [ShippingDetailController::class, 'updateStatus']);
    Route::delete('shipping-details/{id}', [ShippingDetailController::class, 'destroy']);


    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store']);

    Route::post('products', [ProductController::class, 'store']);

    Route::get('my-products', [ProductController::class, 'myproducts']);
    Route::get('payment-options', [PaymentOptionController::class, 'index']);

    Route::get('swaps/requester', [ProductSwapController::class, 'getRequesterSwaps']);
    Route::get('swaps/owner', [ProductSwapController::class, 'getOwnerSwaps']);
    Route::post('swap/request', [ProductSwapController::class, 'requestSwap'])->name('swap.request');
    Route::post('swap/accept/{id}', [ProductSwapController::class, 'acceptSwap'])->name('swap.accept');
    Route::post('swap/reject/{id}', [ProductSwapController::class, 'rejectSwap'])->name('swap.reject');

    Route::post('product-review', [ReviewController::class, 'storeReview']);


});