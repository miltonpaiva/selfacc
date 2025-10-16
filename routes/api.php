<?php

use Illuminate\Support\Facades\Route;

// product
Route::post('/new-product',    [App\Http\Controllers\ProductController::class, 'upInsertProduct'])->name('product.new');
Route::post('/update-product', [App\Http\Controllers\ProductController::class, 'upInsertProduct'])->name('product.update');

// customer
Route::post('/new-customer',    [App\Http\Controllers\CustomerController::class, 'upInsertCustomer'])->name('customer.new');
Route::post('/update-customer', [App\Http\Controllers\CustomerController::class, 'upInsertCustomer'])->name('customer.update');

// account
Route::post('/new-account',    [App\Http\Controllers\AccountController::class, 'createAccount'])->name('account.new');
Route::post('/update-account', [App\Http\Controllers\AccountController::class, 'upInsertAccount'])->name('account.update');
Route::get('/logout',         [App\Http\Controllers\AccountController::class, 'logout'])->name('account.logout');

// order
Route::post('/new-order',    [App\Http\Controllers\OrderController::class, 'upInsertOrder'])->name('order.new');
Route::post('/update-order', [App\Http\Controllers\OrderController::class, 'upInsertOrder'])->name('order.update');


// music
Route::get('/music-request-code',  [App\Http\Controllers\MusicQueueController::class, 'requestAuthCode'])->name('music.request_auth_code');
Route::get('/music-code-callback', [App\Http\Controllers\MusicQueueController::class, 'saveCode'])->name('music.code_callback');
Route::get('/music-get-devices',   [App\Http\Controllers\MusicQueueController::class, 'getDevices'])->name('music.get_devices');
Route::post('/music-set-device',   [App\Http\Controllers\MusicQueueController::class, 'setDevice'])->name('music.set_device');
Route::any('/music-get-queue',     [App\Http\Controllers\MusicQueueController::class, 'getQueue'])->name('music.get_queue');
Route::post('/music-search',       [App\Http\Controllers\MusicQueueController::class, 'search'])->name('music.search');
Route::post('/music-queue-add',    [App\Http\Controllers\MusicQueueController::class, 'addQueue'])->name('music.add_queue');
