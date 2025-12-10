<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

# Make sure your routes are set up in routes/web.php
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login_view');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login');


Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');
});
//     // Users
//     Route::resource('users', UserController::class);

//     // Perfumes
//     Route::resource('perfumes', PerfumeController::class);
//     Route::resource('concentrations', ConcentrationController::class);

//     // Packages
//     Route::resource('packages', PackageController::class);

//     // Bottles
//     Route::resource('bottles', BottleController::class);

//     // Orders
//     Route::resource('orders', OrderController::class);

//     // Reviews
//     Route::resource('reviews', ReviewController::class);

//     // Locations
//     Route::resource('countries', CountryController::class);
//     Route::resource('cities', CityController::class);

//     // Settings
//     Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
//     Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

// });

// // Redirect root to dashboard
// Route::redirect('/', '/dashboard');

