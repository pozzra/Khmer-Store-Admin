<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin CRUD routes
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin.index');
Route::get('/admin/create', [AdminController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('admin.create');
Route::post('/admin', [AdminController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.store');
Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('admin.edit');
Route::put('/admin/{id}', [AdminController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.update');
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('admin.destroy');

// Student CRUD routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('products.create');
Route::post('/products', [ProductController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('products.store');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('products.destroy');

// Stock management routes
Route::get('/stock', [ProductController::class, 'stockIndex'])->name('stock.index');
Route::get('/stock/create', [ProductController::class, 'stockCreate'])->middleware(['auth', 'verified'])->name('stock.create');
Route::post('/stock', [ProductController::class, 'store'])->middleware(['auth', 'verified'])->name('stock.store');
Route::get('/stock/{id}/edit', [ProductController::class, 'stockEdit'])->middleware(['auth', 'verified'])->name('stock.edit');
Route::put('/stock/{id}', [ProductController::class, 'update'])->middleware(['auth', 'verified'])->name('stock.update');
Route::delete('/stock/{id}', [ProductController::class, 'destroy'])->middleware(['auth', 'verified'])->name('stock.destroy');

// Report management routes
Route::get('/report', [ReportController::class, 'index'])->name('report.index');
Route::get('/report/create', [ReportController::class, 'create'])->middleware(['auth', 'verified'])->name('report.create');
Route::post('/report', [ReportController::class, 'store'])->middleware(['auth', 'verified'])->name('report.store');
Route::get('/report/{id}/edit', [ReportController::class, 'edit'])->middleware(['auth', 'verified'])->name('report.edit');
Route::put('/report/{id}', [ReportController::class, 'update'])->middleware(['auth', 'verified'])->name('report.update');
Route::delete('/report/{id}', [ReportController::class, 'destroy'])->middleware(['auth', 'verified'])->name('report.destroy');

// Order management routes
Route::get('/order', [\App\Http\Controllers\OrderController::class, 'index'])->name('order.index');
Route::get('/order/create', [\App\Http\Controllers\OrderController::class, 'create'])->middleware(['auth', 'verified'])->name('order.create');
Route::post('/order', [\App\Http\Controllers\OrderController::class, 'store'])->middleware(['auth', 'verified'])->name('order.store');
Route::get('/order/{id}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->middleware(['auth', 'verified'])->name('order.edit');
Route::put('/order/{id}', [\App\Http\Controllers\OrderController::class, 'update'])->middleware(['auth', 'verified'])->name('order.update');
Route::delete('/order/{id}', [\App\Http\Controllers\OrderController::class, 'destroy'])->middleware(['auth', 'verified'])->name('order.destroy');


Route::get('/report/export-excel', [ReportController::class, 'exportExcel']);
Route::get('/report/export-pdf', [ReportController::class, 'exportPDF']);

Route::get('/api/products', function () {
    return response()->json(Product::all());
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
