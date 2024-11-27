<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockLogController;
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

Route::controller(UserController::class)->prefix("/users")->group(function () {
    Route::get("/", "index");
    Route::put("/{id}", 'update');
    Route::delete("/{id}", 'destroy');

    Route::post("/register", "store");
})->middleware(['auth:sanctum', "CheckNotCommonUser"]);

Route::controller(CategoryController::class)->prefix("/categories")->group(function () {
    Route::get("/", "index");
    Route::post("/", "store")->middleware(["CheckNotCommonUser"]);
    Route::put("/{id}", "update")->middleware(["CheckNotCommonUser"]);
    Route::delete("/{id}", "destroy")->middleware(["CheckNotCommonUser"]);
})->middleware('auth:sanctum');

Route::controller(SupplierController::class)->prefix("/suppliers")->group(function () {
    Route::get("/", "index");
    Route::post("/", "store")->middleware(["CheckNotCommonUser"]);
    Route::put("/{id}", "update")->middleware(["CheckNotCommonUser"]);
    Route::delete("/{id}", "destroy")->middleware(["CheckNotCommonUser"]);
})->middleware('auth:sanctum');

Route::controller(ProductController::class)->prefix("/products")->group(function () {
    Route::get("/", "index");
    Route::post("/", "store")->middleware(["CheckNotCommonUser"]);
    Route::put("/{id}", "update")->middleware(["CheckNotCommonUser"]);
    Route::delete("/{id}", "destroy")->middleware(["CheckNotCommonUser"]);
})->middleware('auth:sanctum');

Route::controller(StockController::class)->prefix("/stocks")->group(function () {
    Route::get("/", "index");
    Route::post("/", "store");
    Route::put("/{id}", "update");
    Route::delete("/{id}", "destroy");
})->middleware(['auth:sanctum', "CheckNotCommonUser"]);

Route::controller(StockLogController::class)->prefix("/stocks/logs")->group(function () {
    Route::get("/", "index");
    Route::post("/", "store");
    Route::put("/{id}", "update")->middleware(["CheckJustAdmin"]);
    Route::delete("/{id}", "destroy")->middleware(["CheckJustAdmin"]);
})->middleware(['auth:sanctum']);

Route::controller(ListingController::class)->prefix("/listing")->group(function () {
    Route::get("/lowstock", 'getLowStock');
    Route::get("/expiration", 'getCloseToExpirationDate');
})->middleware(['auth:sanctum']);
