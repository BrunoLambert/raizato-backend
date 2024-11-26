<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post("/login", [LoginController::class, 'login']);
Route::post("/logout", [LoginController::class, 'logout'])->middleware(['auth:sanctum']);

Route::prefix('/startup')->group(function () {
    Route::get("/", [LoginController::class, "checkAdmin"]);
    Route::post("/", [LoginController::class, "initAdmin"]);
});

Route::prefix("/user")->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::put("/", [UserController::class, 'edit'])->middleware('auth:sanctum');
});

Route::controller(UserController::class)->group(function () {
    Route::prefix("/users")->group(function () {
        Route::get("/", "index")->middleware("CheckNotCommonUser");
        Route::put("/{id}", 'update')->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::delete("/{id}", 'destroy')->middleware(['auth:sanctum', "CheckNotCommonUser"]);

        Route::post("/register", "store")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
    });
});

Route::controller(CategoryController::class)->group(function () {
    Route::prefix("/categories")->group(function () {
        Route::get("/", "index")->middleware('auth:sanctum');
        Route::post("/", "store")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::put("/{id}", "update")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::delete("/{id}", "destroy")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
    });
});

Route::controller(SupplierController::class)->group(function () {
    Route::prefix("/suppliers")->group(function () {
        Route::get("/", "index")->middleware('auth:sanctum');
        Route::post("/", "store")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::put("/{id}", "update")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::delete("/{id}", "destroy")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
    });
});

Route::controller(ProductController::class)->group(function () {
    Route::prefix("/products")->group(function () {
        Route::get("/", "index")->middleware('auth:sanctum');
        Route::post("/", "store")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::put("/{id}", "update")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
        Route::delete("/{id}", "destroy")->middleware(['auth:sanctum', "CheckNotCommonUser"]);
    });
});
