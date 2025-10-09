<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// product
Route::post('/new-product',    [App\Http\Controllers\ProductController::class, 'upInsertProduct'])->name('product.new');
Route::post('/update-product', [App\Http\Controllers\ProductController::class, 'upInsertProduct'])->name('product.new');