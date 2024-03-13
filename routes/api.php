<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post("/register", [UserController::class, "store"]);
Route::post("/login", [UserController::class, "login"]);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get("/proflie/{user}", [UserController::class, "show"]);
    Route::put("/update/{user}", [UserController::class, "update"]);
    Route::put("/changstate/{user}", [UserController::class, "changstate"]);
    Route::delete("/delete/{user}", [UserController::class, "destroy"]);
    Route::post("/logout", [UserController::class, "logout"]);

    Route::get("/categories", [CategoryController::class, "index"]);
    Route::post("/category", [CategoryController::class, "store"]);
    Route::get("/category/{category}", [CategoryController::class, "show"]);
    Route::put('/category/{category}', [CategoryController::class, "update"]);
    Route::delete('/category/{category}', [CategoryController::class, "destroy"]);

    Route::get("/products", [ProductController::class, "index"]);
    Route::post("/product", [ProductController::class, "store"]);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::post('/product/{product}', [ProductController::class, 'update']);
    Route::delete('/product/{product}', [ProductController::class, 'destroy']);

    Route::get("/images", [ProductImageController::class, "index"]);
    Route::post("/image", [ProductImageController::class, "store"]);
    Route::get('/image/{productImage}', [ProductImageController::class, 'show']);
    Route::post('/image/{productImage}', [ProductImageController::class, 'update']);
    Route::delete('/image/{productImage}', [ProductImageController::class, 'destroy']);
});

Route::get('/cart/{user}', [CartController::class, 'show']);
Route::post('/cart', [CartController::class, "store"]);
Route::put('/cart/{cart}',[CartController::class,'update']);
Route::delete('/cart/{cart}', [CartController::class, "destroy"]);

Route::get("/order/{user}", [OrderController::class,"show"]);
Route::post("/order", [OrderController::class,"store"]);

Route::get("/orderdetail/{order}", [OrderDetailController::class,"show"]);
Route::post("/orderdetail", [OrderDetailController::class,"store"]);