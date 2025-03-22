<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout',  [AuthController::class,'logout']);
Route::get('me', [AuthController::class,'getUser']);      

Route::middleware([IsUserAuth::class])->group(function(){
    
});

Route::get('products', [ProductController::class, 'getProducts']);
Route::post('products',  [ProductController::class, 'addProduct']);
Route::get('/products/{id}', [ProductController::class, 'getProduct']);
Route::patch('/products/{id}', [ProductController::class, 'updateProduct']);
Route::delete('/products/{id}', [ProductController::class, 'deleteProductById']);

Route::middleware([IsAdmin::class])->group(function(){
    Route::controller(AuthController::class)->group(function () {
        Route::get('users', 'listUsers');
        Route::patch('users/{id}', 'updateUser');  
        Route::delete('users/{id}', 'deleteUser');   
    });
});

