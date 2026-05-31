<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;




use App\Http\Controllers\ShopLoginController;

use App\Http\Controllers\ShopDashboardController;
use App\Http\Controllers\ShopItemInfoController;
use App\Http\Controllers\ShopCartController;
use App\Http\Controllers\ShopProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware'=>['guest']],function(){

    Route::get('/shop/login',[ShopLoginController::class,'index'])->name('shop.login');
    Route::post('/shop/login',[ShopLoginController::class,'login'])->name('shop.login.post');

    Route::get('/',[ShopDashboardController::class,'index'])->name('dashboard.index');
    Route::get('/shop/items',[ShopDashboardController::class,'store'])->name('dashboard.items');

    Route::get('/item/info/{id}',[ShopItemInfoController::class,'index'])->name('itemdetails.index');

    Route::get('/cart/list',[ShopCartController::class,'index'])->name('cart.index');

    Route::get('/profile/account',[ShopProfileController::class,'index'])->name('profile.account');
    

    Route::get('/erroradmin',[LoginController::class,'getLogin'])->name('getLogin');
    Route::post('/login',[LoginController::class,'postLogin'])->name('postLogin');
});

Route::group(['middleware'=>['login_auth']],function(){
    Route::get('/dashboard/view', [DashboardController::class, 'index'])->name('dash-index');
    Route::post('/logout',[DashboardController::class,'logout'])->name('logout');

    Route::prefix('categories')->group(function () {  
        Route::get('/list/cat/view', [CategoryController::class, 'index'])->name('category.index');
        Route::post('/list/cat/store', [CategoryController::class, 'store'])->name('category.store');
        Route::get('/list/cat/show', [CategoryController::class, 'show'])->name('category.show');
        Route::post('/list/cat/update', [CategoryController::class, 'update'])->name('category.update');
        Route::post('/list/cat/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });

    Route::prefix('products')->group(function () {  
        Route::get('/list/view', [ProductsController::class, 'index'])->name('product.index');
        Route::post('/list/store', [ProductsController::class, 'store'])->name('product.store');
        Route::get('/list/show', [ProductsController::class, 'show'])->name('product.show');
        Route::post('/list/update', [ProductsController::class, 'update'])->name('product.update');
        Route::post('/list/destroy/{id}', [ProductsController::class, 'destroy'])->name('product.destroy');
    });

    Route::prefix('customers')->group(function () {  
        Route::get('/list/view', [CustomerController::class, 'index'])->name('customer.index');
        Route::post('/list/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/list/show', [CustomerController::class, 'show'])->name('customer.show');
    });

    Route::prefix('users')->group(function () {  
        Route::get('/list/view', [UserController::class, 'index'])->name('user.index');
        Route::post('/list/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/list/show', [UserController::class, 'show'])->name('user.show');
        Route::post('/list/update', [UserController::class, 'update'])->name('user.update');
        Route::post('/list/destroy/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::post('list/updatePass', [UserController::class, 'userUpdatePassword'])->name('userUpdatePassword');
        Route::post('list/updateStatusnow', [UserController::class, 'userUpdateStatus'])->name('userUpdateStatus');
    });

    Route::prefix('welcomebanner')->group(function () {  
        Route::get('/list/view', [BannerController::class, 'index'])->name('welcomebanner.index');
        Route::post('/list/store', [BannerController::class, 'store'])->name('welcomebanner.store');
        Route::get('/list/show', [BannerController::class, 'show'])->name('welcomebanner.show');
    });
});
