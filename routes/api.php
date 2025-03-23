<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//posts 
Route::apiResource('/posts', App\Http\Controllers\Api\PostController::class);
//kategori
Route::apiResource('/kategori', App\Http\Controllers\Api\KategoriController::class);
//produk
Route::apiResource('/produk', App\Http\Controllers\Api\ProdukController::class);
//staff
Route::apiResource('/staff', App\Http\Controllers\Api\StaffController::class);
