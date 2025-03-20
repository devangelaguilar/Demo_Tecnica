<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PerfilController;



Route::controller(ProductoController::class)->group(function () { 
    Route::get('/productos', 'index'); 
    Route::get('/productos/{id}', 'show'); 
    Route::post('/productos', 'store'); 
    Route::put('/productos/{id}', 'update'); 
    Route::delete('/productos/{id}', 'destroy'); 
});

Route::controller(UsuarioController::class)->group(function () { 
    Route::get('/usuarios', 'index'); 
    Route::get('/usuarios/{id}', 'show'); 
    Route::post('/usuarios', 'store'); 
    Route::put('/usuarios/{id}', 'update'); 
    Route::delete('/usuarios/{id}', 'destroy'); 
});

Route::controller(PerfilController::class)->group(function () { 
    Route::get('/perfil', 'index'); 
    Route::get('/perfil/{id}', 'show'); 
    Route::post('/perfil', 'store'); 
    Route::put('/perfil/{id}', 'update'); 
    Route::delete('/perfil/{id}', 'destroy'); 
});
