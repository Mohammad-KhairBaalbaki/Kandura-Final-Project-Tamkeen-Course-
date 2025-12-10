<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\DesignOptionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'apiRegister');
    Route::post('/login', 'apiLogin');
});


Route::middleware('auth:sanctum')->group(function () {

    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile');
        Route::put('/profile/{user}', 'update');


    });

    Route::prefix('addresses')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{address}', 'update');
        Route::delete('/{address}', 'destroy');
    });

    Route::prefix('admins')->controller(AdminController::class)->group(function () {
        // Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{user}', 'update');
        Route::delete('/{user}', 'destroy');

    });

    Route::prefix('design-options')->controller(DesignOptionController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/','store');
        Route::put('/{designOption}', 'update');
        Route::delete('/{designOption}', 'destroy');
    });

    //designs
    Route::prefix('designs')->controller(DesignController::class)->group(function () {
        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');
        Route::get('/my-designs', 'myDesigns');
        Route::post('/','store');
        Route::put('/{design}', 'update');
        Route::delete('/{design}', 'destroy');
    });


    //add to cart
    Route::prefix('cart')->group(function(){
        Route::post('/add', [CartController::class, 'store']);
    });

});
