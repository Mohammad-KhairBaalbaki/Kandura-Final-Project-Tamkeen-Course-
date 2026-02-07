<?php

use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\FcmTokenController;
use App\Http\Controllers\Web\AdminController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CouponController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DesignController;
use App\Http\Controllers\Web\DesignOptionController;
use App\Http\Controllers\Web\LanguageController;
use App\Http\Controllers\Web\LocationController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\ReviewController;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\SettingsController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\WalletController;
use App\Http\Middleware\CheckActiveMiddleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

// Show welcome page (requires auth)
Route::get('/', function () {
    return view('welcome');
})->middleware('auth');

// FCM token registration (web)
// Store FCM token for logged-in user
Route::middleware('auth')->post('/fcm/token', [FcmTokenController::class, 'store']);

// Auth (web)
// Show login form
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_view');
// Handle login submit
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Language switcher
// Switch UI language
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Stripe webhook (no CSRF)
// Handle Stripe webhook (no CSRF)
Route::post('/stripe/webhook', [WebhookController::class, 'handle'])
    ->withoutMiddleware([
        VerifyCsrfToken::class])->name('stripe.webhook');

// Protected routes (auth)
Route::middleware(['auth:sanctum'])->group(function () {

    // Dashboard
    // Dashboard home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    // Admin profile page
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    // Settings
    // Settings index
    Route::get('/settings', [SettingsController::class, 'index'])
        ->middleware([CheckActiveMiddleware::class])
        ->name('settings.index');

    // Update notification preferences
    Route::post('/settings/notifications', [SettingsController::class, 'updateNotifications'])
        ->middleware([CheckActiveMiddleware::class])
        ->name('settings.notifications.update');

    // Logout
    // Logout current user
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Orders
    Route::prefix('orders')->middleware([CheckActiveMiddleware::class])->name('orders.')->group(function () {

        // List orders
        Route::get('/', [OrderController::class, 'index'])->name('index')->middleware('permission:view-orders,api');

        // Show order details
        Route::get('/show/{order}', [OrderController::class, 'show'])->name('show')->middleware('permission:view-orders,api');

        // Download order invoice PDF
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice')->middleware('permission:view-invoices,api');

        // Download invoices as ZIP
        Route::post('/invoices/zip', [OrderController::class, 'downloadInvoicesZip'])->middleware('permission:view-invoices,api')->name('invoices.zip');

        // Update order status
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:edit-orders,api');

        // Show failed payment for order
        Route::get('/{order}/failed', [OrderController::class, 'failed'])->name('failed');

    });

    // Payments
    Route::prefix('payments')->middleware([CheckActiveMiddleware::class])->name('payments.')->group(function () {
        // List payments
        Route::get('/', [PaymentController::class, 'index'])->name('index')
            ->middleware('permission:view-invoices,api');
    });

    // Users
    Route::prefix('users')->middleware([CheckActiveMiddleware::class])->controller(UserController::class)->name('users.')->group(function () {
        // List users
        Route::get('/', 'index')->name('index')
            ->middleware('permission:view-users,api');

        // Show user details
        Route::get('/{user}', 'show')->name('show')
            ->middleware('permission:view-users,api');

        // Update user active status
        Route::put('/{user}/status', [UserController::class, 'updateStatus'])
            ->name('updateStatus')
            ->middleware('permission:disable-accounts,api');
    });

    // Admins
    Route::prefix('admins')->middleware([CheckActiveMiddleware::class])->name('admins.')->group(function () {
        // List admins
        Route::get('/', [AdminController::class, 'index'])->name('index')
            ->middleware('permission:view-admins,api');
        // List deleted admins
        Route::get('/trashed', [AdminController::class, 'trashed'])->name('trashed')
            ->middleware('permission:view-admins,api');

        // Show create admin form
        Route::get('/create', [AdminController::class, 'create'])->name('create')
            ->middleware('permission:add-admins,api');
        // Store new admin
        Route::post('/store', [AdminController::class, 'store'])->name('store')
            ->middleware('permission:add-admins,api');
        // Show admin details
        Route::get('/{user}', [AdminController::class, 'show'])->name('show')
            ->middleware('permission:view-admins,api');
        // Show edit admin form
        Route::get('/{user}/edit', [AdminController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-admins,api');
        // Update admin
        Route::put('/{user}', [AdminController::class, 'update'])->name('update')
            ->middleware('permission:edit-admins,api');
        // Restore admin
        Route::put('/{user}/restore', [AdminController::class, 'restore'])->name('restore')
            ->middleware('permission:delete-admins,api');
        // Delete admin
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('destroy')
            ->middleware('permission:delete-admins,api');
    });

    // Designs
    Route::prefix('designs')->middleware([CheckActiveMiddleware::class])->name('designs.')->group(function () {
        // List designs
        Route::get('/', [DesignController::class, 'index'])->name('index');

        // Show design details
        Route::get('/{design}', [DesignController::class, 'show'])->name('show');

        // Update design status
        Route::put('/{design}/status', [DesignController::class, 'updateStatus'])->name('updateStatus')->middleware('permission:disable-designs,api');
    });

    // Design Options
    Route::prefix('design_options')->middleware([CheckActiveMiddleware::class])->name('design_options.')->group(function () {
        // List design options
        Route::get('/', [DesignOptionController::class, 'index'])->name('index')
            ->middleware('permission:view-design-options,api');

        // List deleted design options
        Route::get('/trashed', [DesignOptionController::class, 'trashed'])->name('trashed')
            ->middleware('permission:view-design-options,api');

        // Show create design option form
        Route::get('/create', [DesignOptionController::class, 'create'])->name('create')
            ->middleware('permission:create-design-options,api');

        // Store design option
        Route::post('/store', [DesignOptionController::class, 'store'])->name('store')
            ->middleware('permission:create-design-options,api');

        // Show edit design option form
        Route::get('/{designOption}/edit', [DesignOptionController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-design-options,api');

        // Update design option
        Route::put('/{designOption}', [DesignOptionController::class, 'update'])->name('update')
            ->middleware('permission:edit-design-options,api');

        // Update design option status
        Route::put('/{designOption}/status', [DesignOptionController::class, 'updateStatus'])->name('updateStatus')
            ->middleware('permission:edit-design-options,api');

        // Soft delete design option
        Route::delete('/{designOption}', [DesignOptionController::class, 'destroy'])->name('destroy')
            ->middleware('permission:delete-design-options,api');

        // Restore deleted design option
        Route::put('/{designOption}/restore', [DesignOptionController::class, 'restore'])->name('restore')
            ->middleware('permission:delete-design-options,api');
    });

    // Coupons
    Route::prefix('coupons')->middleware([CheckActiveMiddleware::class])->name('coupons.')->group(function () {

        // List coupons
        Route::get('/', [CouponController::class, 'index'])->name('index')
            ->middleware('permission:view-coupons,api');

        // Show create coupon form
        Route::get('/create', [CouponController::class, 'create'])->name('create')
            ->middleware('permission:create-coupons,api');

        // Store coupon
        Route::post('/store', [CouponController::class, 'store'])->name('store')
            ->middleware('permission:create-coupons,api');

        // Show edit coupon form
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-coupons,api');

        // Update coupon
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update')
            ->middleware('permission:edit-coupons,api');

        // Update coupon status
        Route::put('/{coupon}/status', [CouponController::class, 'updateStatus'])->name('updateStatus')
            ->middleware('permission:edit-coupons,api');
    });

    // Wallets
    Route::prefix('wallets')->middleware([CheckActiveMiddleware::class])->name('wallets.')->group(function () {
        // Show wallet charge form
        Route::get('/charge', [WalletController::class, 'charge'])->name('charge')
            ->middleware('permission:add-balance,api');

        // Store wallet charge
        Route::post('/charge', [WalletController::class, 'storeCharge'])->name('storeCharge')
            ->middleware('permission:add-balance,api');

    });

    // Roles
    Route::prefix('roles')->middleware([CheckActiveMiddleware::class])->name('roles.')->group(function () {
        // List roles
        Route::get('/', [RoleController::class, 'index'])->name('index')
            ->middleware('permission:view-roles,api');

        // Show create role form
        Route::get('/create', [RoleController::class, 'create'])->name('create')
            ->middleware('permission:add-roles,api');

        // Store role
        Route::post('/store', [RoleController::class, 'store'])->name('store')
            ->middleware('permission:add-roles,api');

        // Show role details
        Route::get('/{role}', [RoleController::class, 'show'])->name('show')
            ->middleware('permission:view-roles,api');

        // Show edit role form
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit')
            ->middleware('permission:edit-roles,api');

        // Update role
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')
            ->middleware('permission:edit-roles,api');

        // Delete role
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')
            ->middleware('permission:delete-roles,api');

    });

    // Reviews
    Route::prefix('reviews')->middleware([CheckActiveMiddleware::class])->name('reviews.')->group(function () {
        // List reviews
        Route::get('/', [ReviewController::class, 'index'])->name('index')
            ->middleware('permission:view-orders,api');
    });

    // Locations
    Route::prefix('locations')->middleware([CheckActiveMiddleware::class])->name('locations.')->group(function () {
        // Orders by city chart
        Route::get('/cities', [LocationController::class, 'cities'])->name('cities')
            ->middleware('permission:view-orders,api');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        // Notifications list
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        // Mark notification as read
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        // Mark all notifications as read
        Route::post('/mark-read', [NotificationController::class, 'markReadBulk'])->name('markReadBulk');
    });

});
