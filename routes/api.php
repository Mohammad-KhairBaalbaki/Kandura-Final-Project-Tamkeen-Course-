<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\DesignOptionController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\NotificationController ;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckActiveMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});


Route::middleware('auth:sanctum')->group(function () {



    Route::controller(UserController::class)->group(function () {
        Route::get('/profile', 'profile');
        Route::post('/profile/photo', 'updatePhoto');
        Route::put('/profile/{user}', 'update');


    });

    Route::prefix('addresses')->controller(AddressController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/{address}', 'update');
        Route::delete('/{address}', 'destroy');
    });

    Route::prefix('admins')->middleware([CheckActiveMiddleware::class])->controller(AdminController::class)->group(function () {

        // Route::get('/', 'index');
        Route::post('/', 'store')->middleware('permission:add-admins,api');

        Route::put('/{user}', 'update')->middleware('permission:edit-admins,api');

        Route::delete('/{user}', 'destroy')->middleware('permission:delete-admins,api');

    });

    Route::prefix('design-options')->middleware([CheckActiveMiddleware::class])->controller(DesignOptionController::class)->group(function () {
        Route::get('/', 'index')->middleware('permission:view-design-options,api');

        Route::post('/', 'store')->middleware('permission:create-design-options,api');

        Route::put('/{designOption}', 'update')->middleware('permission:edit-design-options,api');

        Route::delete('/{designOption}', 'destroy')->middleware('permission:delete-design-options,api');
    });

    //designs
    Route::prefix('designs')->middleware([CheckActiveMiddleware::class])->controller(DesignController::class)->group(function () {

        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');

        Route::get('/my-designs', 'myDesigns')->withoutMiddleware(CheckActiveMiddleware::class);

        Route::post('/', 'store')->middleware('permission:create-designs,api');

        Route::put('/{design}', 'update')->middleware('permission:edit-designs,api');

        Route::delete('/{design}', 'destroy')->middleware('permission:delete-designs,api');

    });


    //add to cart
    Route::prefix('cart')->middleware([CheckActiveMiddleware::class])->group(function () {
        //my cart
        Route::get('', [CartController::class, 'index'])->middleware('permission:create-orders,api');
        //add to cart
        Route::post('/add', [CartController::class, 'store'])->middleware('permission:create-orders,api');
        //add coupon
        Route::post('/add-coupon', [CartController::class, 'addCoupon'])->middleware('permission:create-orders,api');
        //edit quantity of item
        Route::put('/edit/{item}', [CartController::class, 'update'])->middleware('permission:create-orders,api');
        //delete item
        Route::delete('/remove/{item}',[CartController::class, 'destroy'])->middleware('permission:create-orders,api');
        //remove coupon
        Route::delete('/remove-coupon', [CartController::class, 'removeCoupon'])->middleware('permission:create-orders,api');
    });

    Route::prefix('order')->middleware([CheckActiveMiddleware::class])->group(function () {
        //my orders
        Route::get('/', [OrderController::class, 'index'])->middleware('permission:create-orders,api');
        //crate order
        Route::post('/', [OrderController::class, 'store'])->middleware('permission:create-orders,api');
        Route::post('/pay/{order}', [OrderController::class, 'pay'])->middleware('permission:create-orders,api');
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->middleware('permission:view-invoices,api');
        //cancel order
        Route::put('/{order}', [OrderController::class, 'update'])->middleware('permission:create-orders,api');
        // add review to delivered order
        Route::post('/{order}/review', [ReviewController::class, 'storeForOrder'])
            ->middleware('permission:add-reviews,api');
    });

    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/unread', 'unread');
        Route::get('/unread/count', 'unreadCount');

        Route::put('/{notification}/read', 'markRead');

        Route::put('/read-all', 'markAllRead');


    });


});


//For stripe
Route::prefix('order')->group(function () {
    Route::get('/success-payment/{order}', [OrderController::class, 'successPayment'])->name('success_payment');
    Route::get('/failed-payment', [OrderController::class, 'failedPayment'])->name('failed_payment');
});


