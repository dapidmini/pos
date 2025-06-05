<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
// Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
// Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
// Route::get('/categories/edit', [CategoryController::class, 'edit'])->name('category.edit');
// Route::get('/categories/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::resource('categories', CategoryController::class)->except(['show']);

Route::resource('products', ProductController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('transaksis', TransaksiController::class);