<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;

use App\Services\TempCleaner;


Route::get('/', function (TempCleaner $cleanup) {
    $cleanup->run();
    return view('welcome');
});
// Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
// Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
// Route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
// Route::get('/categories/edit', [CategoryController::class, 'edit'])->name('category.edit');
// Route::get('/categories/destroy', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::resource('categories', CategoryController::class)->except(['show']);

Route::resource('customers', CustomerController::class);

Route::resource('products', ProductController::class);
Route::post('/products/upload-temp', [ProductController::class, 'uploadTemp'])->name('products.upload.temp');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

Route::resource('suppliers', SupplierController::class);
Route::resource('transaksis', TransaksiController::class);

Route::post('/gallery/upload/temp', [GalleryController::class, 'uploadTemp'])->name('gallery.upload.temp');
Route::post('/gallery/upload/temp/delete', [GalleryController::class, 'deleteTemp'])->name('gallery.upload.temp.delete');