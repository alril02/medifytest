<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/kategori', [App\Http\Controllers\Kategori::class, 'index'])->name('kategori');
    Route::get('/kategori/search', [App\Http\Controllers\Kategori::class, 'search']);
    Route::get('/kategori/form/{method}/{id?}', [App\Http\Controllers\Kategori::class, 'formView']);
    Route::post('/kategori/form/{method}/{id?}', [App\Http\Controllers\Kategori::class, 'formSubmit']);
    Route::get('/kategori/delete/{id}', [App\Http\Controllers\Kategori::class, 'delete']);
    Route::get('/kategori/view/{id}', [App\Http\Controllers\Kategori::class, 'singleView']);
    Route::get('/kategori/export-pdf/{id}', [App\Http\Controllers\Kategori::class, 'exportPdf'])->name('kategori.export');

    // Router lama
    Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index'])->name('master-items');
    Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search']);
    Route::get('/master-items/export-csv', [App\Http\Controllers\MasterItemsController::class, 'exportCsv'])->name('master-items.export');
    Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView']);
    Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit']);

    Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView']);
    Route::get('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete']);


    Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData']);

});

require __DIR__.'/auth.php';
