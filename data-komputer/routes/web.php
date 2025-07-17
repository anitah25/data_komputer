<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

//middleware("auth")->
Route::prefix("admin")->group(function () {
    Route::get("/", function () {
        return view("admin.dashboard");
    })->name("admin.dashboard");

    Route::resource("komputer", App\Http\Controllers\Admin\KomputerController::class);
});
