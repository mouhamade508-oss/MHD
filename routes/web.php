<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public landing - products page for everyone
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'index'])->name('products.search');

Route::get('/home', [ProductController::class, 'index'])->name('home');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::post('/product/{product}/reviews', [ProductController::class, 'storeReview'])->name('product.reviews.store');
Route::get('/product/{product}/whatsapp', [ProductController::class, 'whatsapp'])->name('product.whatsapp');

// Admin login redirect
Route::get('/admin', function () {
    if (auth()->check() && auth()->user()->is_admin) {
        return redirect()->route('admin.products.index');
    }
    return redirect()->route('login');
})->name('admin');

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [AdminController::class, 'index'])->name('products.index');
    Route::get('/categories', [AdminController::class, 'categoriesIndex'])->name('categories.index');
    
    // Products CRUD
    Route::get('/products/create', [AdminController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminController::class, 'destroy'])->name('products.destroy');
    
    // Categories CRUD
    Route::get('/categories/create', [AdminController::class, 'categoriesCreate'])->name('categories.create');
    Route::post('/categories', [AdminController::class, 'categoriesStore'])->name('categories.store');
    Route::get('/categories/{category}/edit', [AdminController::class, 'categoriesEdit'])->name('categories.edit');



});

Route::post('/product/{product}/discount', [ProductController::class, 'applyDiscount'])->name('product.discount.apply');

// Admin discounts CRUD
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/discounts', [AdminController::class, 'discountsIndex'])->name('discounts.index');
    Route::get('/discounts/create', [AdminController::class, 'discountsCreate'])->name('discounts.create');
    Route::post('/discounts', [AdminController::class, 'discountsStore'])->name('discounts.store');
    Route::get('/discounts/{discount}/edit', [AdminController::class, 'discountsEdit'])->name('discounts.edit');
    Route::put('/discounts/{discount}', [AdminController::class, 'discountsUpdate'])->name('discounts.update');
    Route::delete('/discounts/{discount}', [AdminController::class, 'discountsDestroy'])->name('discounts.destroy');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';
