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


Route::get('/', [\App\Http\Controllers\MainController::class, 'index'])->name('index');
Route::post('/del_item/', [\App\Http\Controllers\MainController::class, 'delItem'])->name('delItem');
Route::post('/add_item/', [\App\Http\Controllers\MainController::class, 'addItem'])->name('addItem');
Route::post('/save_item/', [\App\Http\Controllers\MainController::class, 'saveItem'])->name('saveItem');
Route::post('/status_item/', [\App\Http\Controllers\MainController::class, 'statusItem'])->name('statusItem');

// require __DIR__.'/auth.php';
