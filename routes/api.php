<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\DesignOptionController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\NotificationController ;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware\CheckActiveMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Get authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth (api)
Route::controller(AuthController::class)->group(function () {
    // Register
    Route::post('/register', 'register');
    // Login
    Route::post('/login', 'login');
});

// Protected routes (auth)
Route::middleware('auth:sanctum')->group(function () {

    // Profile
    Route::controller(UserController::class)->group(function () {
        // Get profile
        Route::get('/profile', 'profile');
        // Update profile photo
        Route::post('/profile/photo', 'updatePhoto');
        // Update profile data
        Route::put('/profile/{user}', 'update');


    });

    // Addresses
    Route::prefix('addresses')->controller(AddressController::class)->group(function () {
        // List addresses
        Route::get('/', 'index');
        // Create address
        Route::post('/', 'store');
        // Update address
        Route::put('/{address}', 'update');
        // Delete address
        Route::delete('/{address}', 'destroy');
    });

    // Design options (api)
    Route::prefix('design-options')->middleware([CheckActiveMiddleware::class])->controller(DesignOptionController::class)->group(function () {
        // List design options
        Route::get('/', 'index')->middleware('permission:view-design-options,api');

        // Create design option
        Route::post('/', 'store')->middleware('permission:create-design-options,api');

        // Update design option
        Route::put('/{designOption}', 'update')->middleware('permission:edit-design-options,api');

        // Delete design option
        Route::delete('/{designOption}', 'destroy')->middleware('permission:delete-design-options,api');
    });

    // Designs (api)
    Route::prefix('designs')->middleware([CheckActiveMiddleware::class])->controller(DesignController::class)->group(function () {

        // List designs (public)
        Route::get('/', 'index')->withoutMiddleware('auth:sanctum');

        // List my designs
        Route::get('/my-designs', 'myDesigns')->withoutMiddleware(CheckActiveMiddleware::class);

        // Create design
        Route::post('/', 'store')->middleware('permission:create-designs,api');

        // Update design
        Route::put('/{design}', 'update')->middleware('permission:edit-designs,api');

        // Delete design
        Route::delete('/{design}', 'destroy')->middleware('permission:delete-designs,api');

    });


    // Cart
    Route::prefix('cart')->middleware([CheckActiveMiddleware::class])->group(function () {
        //my cart
        // Get my cart
        Route::get('', [CartController::class, 'index'])->middleware('permission:create-orders,api');
        //add to cart
        // Add item to cart
        Route::post('/add', [CartController::class, 'store'])->middleware('permission:create-orders,api');
        //add coupon
        // Apply coupon to cart
        Route::post('/add-coupon', [CartController::class, 'addCoupon'])->middleware('permission:create-orders,api');
        //edit quantity of item
        // Update cart item quantity
        Route::put('/edit/{item}', [CartController::class, 'update'])->middleware('permission:create-orders,api');
        //delete item
        // Remove item from cart
        Route::delete('/remove/{item}',[CartController::class, 'destroy'])->middleware('permission:create-orders,api');
        //remove coupon
        // Remove coupon from cart
        Route::delete('/remove-coupon', [CartController::class, 'removeCoupon'])->middleware('permission:create-orders,api');
    });

    // Orders (api)
    Route::prefix('order')->middleware([CheckActiveMiddleware::class])->group(function () {
        //my orders
        // List my orders
        Route::get('/', [OrderController::class, 'index'])->middleware('permission:create-orders,api');
        //crate order
        // Create order
        Route::post('/', [OrderController::class, 'store'])->middleware('permission:create-orders,api');
        // Pay for order
        Route::post('/pay/{order}', [OrderController::class, 'pay'])->middleware('permission:create-orders,api');
        // Get invoice for order
        Route::get('/{order}/invoice', [InvoiceController::class, 'invoice'])->middleware('permission:view-invoices,api');
        //cancel order
        // Update order (cancel)
        Route::put('/{order}', [OrderController::class, 'update'])->middleware('permission:create-orders,api');
        // add review to delivered order
        // Add review to delivered order
        Route::post('/{order}/review', [ReviewController::class, 'storeForOrder'])
            ->middleware('permission:add-reviews,api');
    });

    // Notifications (api)
    Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
        // List notifications
        Route::get('/', 'index');
        // List unread notifications
        Route::get('/unread', 'unread');
        // Count unread notifications
        Route::get('/unread/count', 'unreadCount');

        // Mark notification as read
        Route::put('/{notification}/read', 'markRead');

        // Mark all notifications as read
        Route::put('/read-all', 'markAllRead');


    });




});

// Stripe callbacks (no auth)
Route::prefix('order')->group(function () {
    // Stripe success callback
    Route::get('/success-payment/{order}', [OrderController::class, 'successPayment'])->name('success_payment');
    // Stripe failed callback
    Route::get('/failed-payment', [OrderController::class, 'failedPayment'])->name('failed_payment');
});
