<?php

use App\Http\Controllers\AclController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\UserController;
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

// TODO: Order


# ACL
Route::prefix('/acl')->group(function () {
    Route::post('/register', [AclController::class, 'register']);
    Route::post('/login', [AclController::class, 'login']);
});

# User
Route::middleware('auth:sanctum')->prefix('/user')->group(function () {
    Route::resource('/', UserController::class);
    Route::put('/change-password', [UserController::class, 'changePass']);
});

# Cart
Route::middleware('auth:sanctum')->prefix('/cart')->group(function () {
    Route::get('/products', [CartProductController::class, 'cartProducts']);
    Route::post('/add-product', [CartProductController::class, 'addProductToCart']);
    Route::delete('/remove-item/{cartProduct}', [CartProductController::class, 'destroy']);
    Route::put('/change-product-quantity', [CartProductController::class, 'changeProductQuantity']);
});

# Category
Route::middleware('auth:sanctum')->prefix('/category')->group(function () {
    Route::resource('/', CategoryController::class);
});

# Product
Route::middleware('auth:sanctum')->prefix('/product')->group(function () {
    Route::resource('/', ProductController::class);
});

# Slider
Route::prefix('/slider')->group(function () {
    Route::resource('/', SliderController::class);
});

# Support
Route::prefix('/support')->group(function () {
    Route::get('/all', [SupportController::class, 'all'])->middleware(['auth:sanctum', 'only-admin']);
    Route::post('/client-request', [SupportController::class, 'clientRequest']);
    Route::post('/admin-response', [SupportController::class, 'adminResponse'])->middleware(['auth:sanctum', 'only-admin']);
});

# Admin
Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminController::class, 'login']);
    Route::get('/all', [AdminController::class, 'all']);
});

# Post
Route::prefix('/post')->group(function () {
    Route::resource('/', PostController::class);
    Route::get('all-for-admin', [PostController::class, 'allForAdmin']);
});


