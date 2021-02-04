<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\Files\FileController;
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

Route::get('/', [AppController::class, 'landing'])->name('landing');

Route::get('/files/{file}', [FileController::class, 'view'])->name('files.view');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

	Route::get('/files', [FileController::class, 'list'])->name('files.list');

});
