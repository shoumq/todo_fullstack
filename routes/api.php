<?php

use App\Http\Controllers\APIController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/', [APIController::class, 'index']);
Route::delete('/delete', [APIController::class, 'delete']);
Route::post('/add', [APIController::class, 'add']);
Route::post('/save', [APIController::class, 'save']);
Route::post('/status', [APIController::class, 'status']);
