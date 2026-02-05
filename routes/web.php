<?php

use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\DesignController;
use App\Http\Controllers\Web\DesignOptionController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\LocationController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\SettingsController;
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

//webhook
Route::post('/stripe/webhook', [WebhookController::class, 'handle'])
    ->withoutMiddleware([
VerifyCsrfToken::class])->name('stripe.webhook');


// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {



    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])
        ->middleware([CheckActiveMiddleware::class])
        ->name('settings.index');
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])
        ->middleware([CheckActiveMiddleware::class])
        ->name('settings.notifications.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Orders
    Route::prefix('orders')->middleware([CheckActiveMiddleware::class])->name('orders.')->group(function () {

        Route::get('/', [OrderController::class,'index'])->name('index')->middleware('permission:view-orders,api');

        Route::get('/show/{order}', [OrderController::class,'show'])->name('show')->middleware('permission:view-orders,api');

        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice')->middleware('permission:view-invoices,api');

        Route::post('/invoices/zip', [OrderController::class, 'downloadInvoicesZip'])->middleware('permission:view-invoices,api')->name('invoices.zip');

        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:edit-orders,api');

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

        Route::get('/trashed', [DesignOptionController::class, 'trashed'])->name('trashed')
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

        Route::delete('/{designOption}', [DesignOptionController::class, 'destroy'])->name('destroy')
            ->middleware('permission:delete-design-options,api');

        Route::patch('/{designOption}/restore', [DesignOptionController::class, 'restore'])->name('restore')
            ->middleware('permission:delete-design-options,api');
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

    // Reviews
    Route::prefix('reviews')->middleware([CheckActiveMiddleware::class])->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index')
            ->middleware('permission:view-orders,api');
    });

    // Locations
    Route::prefix('locations')->middleware([CheckActiveMiddleware::class])->name('locations.')->group(function () {
        Route::get('/cities', [LocationController::class, 'cities'])->name('cities')
            ->middleware('permission:view-orders,api');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/mark-read', [NotificationController::class, 'markReadBulk'])->name('markReadBulk');
    });

});
