<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Middleware\IsSuperAdmin;
use Illuminate\Support\Facades\Route;


// Guest
Route::middleware('guest')->group(function () {

    Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, "login"])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthController::class, "authenticate"])->name('login.post');

    Route::get('/register', [\App\Http\Controllers\Auth\AuthController::class, "register"])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\AuthController::class, "store"])->name('register.post');
});

// Admin
Route::prefix("admin")->middleware(['auth'])->group(function () {
    Route::get("/", [DashboardController::class, 'index'])->name("admin.dashboard");

    Route::post('/logout', [\App\Http\Controllers\Auth\AuthController::class, "logout"])->name('logout');


    Route::middleware(IsSuperAdmin::class)->group(function () {
        Route::resource("komputer", App\Http\Controllers\Admin\KomputerController::class);
    });

    Route::get('/admin/komputer/export', [App\Http\Controllers\Admin\KomputerController::class, 'export'])->name('komputer.export');
    Route::resource("komputer", App\Http\Controllers\Admin\KomputerController::class)->only(['index', 'show']);
    Route::resource("komputer.riwayat", App\Http\Controllers\Admin\RiwayatPerbaikanKomputerController::class)->only(['index', 'show']);
});
