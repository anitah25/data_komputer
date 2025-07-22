<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RiwayatPerbaikanKomputerController;
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
        Route::resource("komputer.riwayat", App\Http\Controllers\Admin\RiwayatPerbaikanKomputerController::class);
    });

    Route::get('/admin/komputer/export', [App\Http\Controllers\Admin\KomputerController::class, 'export'])->name('komputer.export');
    Route::resource("komputer", App\Http\Controllers\Admin\KomputerController::class)->only(['index', 'show']);
    Route::resource("komputer.riwayat", App\Http\Controllers\Admin\RiwayatPerbaikanKomputerController::class)->only(['index', 'show']);

    // Riwayat Perbaikan routes (nested under komputer)
    // Route::get('/admin/komputer/{komputer}/riwayat-perbaikan', [RiwayatPerbaikanKomputerController::class, 'index'])
    //     ->name('riwayat-perbaikan.index');
    // Route::post('/admin/komputer/{komputer}/riwayat-perbaikan', [RiwayatPerbaikanKomputerController::class, 'store'])
    //     ->name('riwayat-perbaikan.store');
    Route::get('/admin/komputer/{komputer}/riwayat-perbaikan/export', [RiwayatPerbaikanKomputerController::class, 'export'])->name('komputer.riwayat.export');
    //     ->name('riwayat-perbaikan.export');
    // Route::delete('/admin/komputer/{komputer}/riwayat-perbaikan/{riwayat}', [RiwayatPerbaikanKomputerController::class, 'destroy'])
    //     ->name('riwayat-perbaikan.destroy');
});
