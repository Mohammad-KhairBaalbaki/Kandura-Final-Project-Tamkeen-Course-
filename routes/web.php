<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\DesignController;
use App\Http\Controllers\Web\DesignOptionController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\FcmTokenController;
use App\Http\Middleware\CheckActiveMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->middleware('auth');



Route::middleware('auth')->post('/fcm/token', [FcmTokenController::class, 'store']);

# Make sure your routes are set up in routes/web.php
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_view');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

Route::post('/stripe/webhook', [WebhookController::class, 'handle'])
    ->withoutMiddleware([
VerifyCsrfToken::class])->name('stripe.webhook');

// Route::get('/pay/{order}', [ControllersOrderController::class, 'pay']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    // One page that decides where to send the user (success/failed)
    Route::get('/payment/result/{order}', [StripeController::class, 'result'])->name('payment.result');
    Route::get('/payment/status/{order}', [StripeController::class, 'status'])->name('payment.status');

    /////////////////////////////////
    Route::get('/payment/success/{order}', [StripeController::class, 'successP'])->name('payment.success');
    Route::get('/payment/failed/{order}', [StripeController::class, 'failedP'])->name('payment.failed');

    ////////////////////////////////
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Orders
    Route::prefix('orders')->middleware([CheckActiveMiddleware::class])->name('orders.')->group(function () {

        Route::get('/', [OrderController::class,'index'])->name('index')->middleware('permission:view-orders,api');

        Route::get('/show/{order}', [OrderController::class,'show'])->name('show')->middleware('permission:view-orders,api');

        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice')->middleware('permission:view-invoices,api');

        Route::post('/invoices/zip', [OrderController::class, 'downloadInvoicesZip'])->middleware('permission:view-invoices,api')->name('invoices.zip');

        Route::get('/failed/{order}', [OrderController::class, 'failed'])->name('failed');

    });

    // Payments
    Route::prefix('payments')->middleware([CheckActiveMiddleware::class])->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index')
            ->middleware('permission:view-invoices,api');
    });

    // Users
    Route::prefix('users')->middleware([CheckActiveMiddleware::class])->controller(UserController::class)->name('users.')->group(function () {
        Route::get('/', 'index')->name('index')
            ->middleware('permission:view-users,api');

        Route::get('/{user}', 'show')->name('show')
            ->middleware('permission:view-users,api');

        Route::patch('/{user}/status', [UserController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:disable-accounts,api');
    });

    //Admins
    Route::prefix('admins')->middleware([CheckActiveMiddleware::class])->name('admins.')->group(function () {
        Route::get('/', [AdminController::class,'index'])->name('index')
            ->middleware('permission:view-admins,api');

        Route::get('/create', [AdminController::class,'create'])->name('create')
            ->middleware('permission:add-admins,api');
        Route::post('/store', [AdminController::class,'store'])->name('store')
            ->middleware('permission:add-admins,api');
        Route::get('/{user}', [AdminController::class, 'show'])->name('show')
            ->middleware('permission:view-admins,api');
        Route::get('/{user}/edit', [AdminController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-admins,api');
        Route::patch('/{user}', [AdminController::class, 'update'])->name('update')
            ->middleware('permission:edit-admins,api');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy')
            ->middleware('permission:delete-admins,api');
    });

    // Designs
    Route::prefix('designs')->middleware([CheckActiveMiddleware::class])->name('designs.')->group(function () {
        Route::get('/', [DesignController::class, 'index'])->name('index');

        Route::get('/{design}', [DesignController::class, 'show'])->name('show');

        Route::patch('/{design}/status', [DesignController::class, 'updateStatus'])->name('updateStatus')->middleware('permission:disable-designs,api');
    });

    // Design Options
    Route::prefix('design_options')->middleware([CheckActiveMiddleware::class])->name('design_options.')->group(function () {
        Route::get('/', [DesignOptionController::class, 'index'])->name('index')
            ->middleware('permission:view-design-options,api');

        Route::get('/create', [DesignOptionController::class, 'create'])->name('create')
            ->middleware('permission:create-design-options,api');

        Route::post('/store', [DesignOptionController::class, 'store'])->name('store')
            ->middleware('permission:create-design-options,api');

        Route::get('/{designOption}/edit', [DesignOptionController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-design-options,api');

        Route::patch('/{designOption}', [DesignOptionController::class, 'update'])->name('update')
            ->middleware('permission:edit-design-options,api');

        Route::patch('/{designOption}/status', [DesignOptionController::class, 'updateStatus'])->name('updateStatus')
            ->middleware('permission:edit-design-options,api');
    });

    // Coupons
    Route::prefix('coupons')->middleware([CheckActiveMiddleware::class])->name('coupons.')->group(function () {

        Route::get('/', [CouponController::class, 'index'])->name('index')
            ->middleware('permission:view-coupons,api');

        Route::get('/create', [CouponController::class, 'create'])->name('create')
            ->middleware('permission:create-coupons,api');

        Route::post('/store', [CouponController::class, 'store'])->name('store')
            ->middleware('permission:create-coupons,api');

        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-coupons,api');

        Route::patch('/{coupon}', [CouponController::class, 'update'])->name('update')
            ->middleware('permission:edit-coupons,api');

        Route::patch('/{coupon}/status', [CouponController::class, 'updateStatus'])->name('updateStatus')
            ->middleware('permission:edit-coupons,api');
    });

    // Wallets
    Route::prefix('wallets')->middleware([CheckActiveMiddleware::class])->name('wallets.')->group(function () {
        Route::get('/charge', [WalletController::class, 'charge'])->name('charge')
            ->middleware('permission:add-balance,api');

        Route::post('/charge', [WalletController::class, 'storeCharge'])->name('storeCharge')
            ->middleware('permission:add-balance,api');

    });

    // Roles
    Route::prefix('roles')->middleware([CheckActiveMiddleware::class])->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index')
            ->middleware('permission:view-roles,api');

        Route::get('/create', [RoleController::class, 'create'])->name('create')
            ->middleware('permission:add-roles,api');

        Route::post('/store', [RoleController::class, 'store'])->name('store')
            ->middleware('permission:add-roles,api');

        Route::get('/{role}', [RoleController::class, 'show'])->name('show')
            ->middleware('permission:view-roles,api');

        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-roles,api');

        Route::patch('/{role}', [RoleController::class, 'update'])->name('update')
            ->middleware('permission:edit-roles,api');

        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')
            ->middleware('permission:delete-roles,api');

    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/mark-read', [NotificationController::class, 'markReadBulk'])->name('markReadBulk');
    });

});
