<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('index');

Route::get('/admin', [App\Http\Controllers\AccountController::class, 'indexAdmin'])->name('index.admin');

Route::get('/painel-gerencial', [App\Http\Controllers\AccountController::class, 'painelGerencial'])->name('painel.gerencial');
Route::post('/painel-gerencial/update', action: [App\Http\Controllers\AccountController::class, 'update'])->name('painel-gerencial.update');

Route::prefix('admin')->group(function() {
    // Produtos
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::put('/products/{id}', [App\Http\Controllers\ProductController::class, 'update']);
    Route::post('/products', [App\Http\Controllers\ProductController::class, 'store'])->name('products.create');
    
    // Categorias
    Route::get('/categories', [App\Http\Controllers\ProductController::class, 'getCategories'])->name('categories.list');
    Route::post('/categories', [App\Http\Controllers\ProductController::class, 'createCategory'])->name('categories.create');
    Route::post('/categories/{id}/update', [App\Http\Controllers\ProductController::class, 'updateCategory'])->name('categories.update');
    Route::post('/categories/{id}/delete', [App\Http\Controllers\ProductController::class, 'deleteCategory'])->name('categories.delete');
});
