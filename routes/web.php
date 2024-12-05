<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'authentication', 'as' => 'authentication', 'middleware' => ['guest']], function () {
    Route::get('/login', [AuthenticationController::class, 'getLogin'])->name('.login');
    Route::post('/post-login', [AuthenticationController::class, 'postLogin'])->name('.postLogin');

    Route::get('/register', [AuthenticationController::class, 'getRegister'])->name('.register');
    Route::post('/post-register', [AuthenticationController::class, 'postRegister'])->name('.postRegister');
    Route::post('/logOut', [AuthenticationController::class, 'logOut'])->name('.logOut')->withoutMiddleware(['guest']);
});

Route::get('/homeShop', [ProductController::class, 'getHome'])->name('product.homeShop');
Route::group(['prefix' => 'product', 'as' => 'product'], function () {
    Route::get('/searchProduct', [ProductController::class, 'getProduct'])->name('.searchProduct');
    Route::post('/searchProduct', [ProductController::class, 'getProduct'])->name('.listSearch');
    Route::get('{productId}/detailProduct', [ProductController::class, 'getDetailProduct'])->name('.detailProduct');
    Route::get('/listProduct', [ProductController::class, 'getListProduct'])->name('.listProduct');
    Route::get('/search', [ProductController::class, 'search'])->name('.search');
});


Route::group(['prefix' => 'customer', 'as' => 'customer', 'middleware' => ['auth']], function () {
    Route::get('/profile', [CustomerController::class, 'getProfile'])->name('.profile');
    Route::post('/changeProfile', [CustomerController::class, 'changeProfile'])->name('.changeProfile');
    Route::get('/favourite', [CustomerController::class, 'getFavourite'])->name('.favourite');
    Route::post('/favourite/toggle', [CustomerController::class, 'toggleFavourite'])->name('.favourite.toggle');
    Route::get('/cart', [CustomerController::class, 'getCart'])->name('.cart');
    Route::post('/updateCart', [CustomerController::class, 'updateCart'])->name('.updateCart');
    Route::post('/addCart', [CustomerController::class, 'addCart'])->name('.addCart');
    Route::post('/deleteCart', [CustomerController::class, 'deleteCart'])->name('.deleteCart');
    Route::get('/order', [CustomerController::class, 'order'])->name('.order');
    Route::post('/addOrder', [CustomerController::class, 'addOrder'])->name('.addOrder');
    Route::post('/placeOrder', [CustomerController::class, 'placeOrder'])->name('.placeOrder');
    Route::get('/finishOrder', [CustomerController::class, 'finishOrder'])->name('.finishOrder');
    Route::get('/bill', [CustomerController::class, 'getBill'])->name('.bill');
    Route::get('/cartCount', [CustomerController::class, 'cartCount'])->name('.cartCount');
});

Route::group(['prefix' => 'admin', 'as' => 'admin', 'middleware' => ['guest']], function () {
    Route::get('/login', [AdminController::class, 'getLogin'])->name('.login');
    Route::post('/logOut', [AdminController::class, 'logOut'])->name('.logOut')->withoutMiddleware(['guest']);
});

Route::group(['prefix' => 'admin', 'as' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/overview', [AdminController::class, 'getOverview'])->name('.overview');
    Route::get('/orderCustomer', [AdminController::class, 'getOrderCustomer'])->name('.orderCustomer');
    Route::get('/{orderId}/detailOrder', [AdminController::class, 'getDetailOrder'])->name('.detailOrder');
    Route::get('/productManage', [AdminController::class, 'getProductManage'])->name('.productManage');
    Route::get('/addProduct', [AdminController::class, 'addProduct'])->name('.addProduct');
    Route::post('/saveAdd', [AdminController::class, 'saveAdd'])->name('.saveAdd');
    Route::post('/saveEdit/{productId}', [AdminController::class, 'saveEdit'])->name('.saveEdit');
    Route::post('/deleteProduct', [AdminController::class, 'deleteProduct'])->name('.deleteProduct');
});
