<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get("/startup", [UserController::class, 'initAdmin']);
Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->middleware(['auth:sanctum', 'web']);

Route::prefix("/user")->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::put("/", [UserController::class, 'edit'])->middleware('auth:sanctum');
});

Route::prefix("/users")->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get("/", "index")->middleware("CheckNotCommonUser");
        Route::put("/update/{id}", 'update')->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::put("/delete/{id}", 'destroy')->middleware(['auth:sanctum', "CheckNotCommonUser"]);

        Route::post("/register", "store");
    });
});
