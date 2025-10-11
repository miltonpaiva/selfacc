<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('index');

Route::get('/admin', [App\Http\Controllers\AccountController::class, 'indexAdmin'])->name('index.admin');
