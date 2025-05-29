<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
Route::get('/students', [StudentController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])
    ->middleware(['auth', 'verified'])
    ->name('students.create');
Route::post('/students', [StudentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('students.store');
Route::get('/students/{id}/edit', [StudentController::class, 'edit'])
    ->middleware(['auth', 'verified'])
    ->name('students.edit');
Route::put('/students/{id}', [StudentController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('students.update');
Route::delete('/students/{id}', [StudentController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('students.destroy');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
