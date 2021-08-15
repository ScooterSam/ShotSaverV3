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

Route::get('sso/{token}', [AppController::class, 'sso'])->name('sso');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {


	Route::get('/files', [FileController::class, 'list'])->name('files.list');
	Route::get('/files/favourites', [FileController::class, 'favourites'])->name('files.favourites');
	Route::delete('/files/{file}', [FileController::class, 'delete'])->name('files.delete');
	Route::post('/files/{file}/favourite', [FileController::class, 'favourite'])->name('files.favourite');
	Route::put('/files/{file}/update', [FileController::class, 'update'])->name('files.update');

});

Route::get('/files/{file}', [FileController::class, 'view'])->name('files.view');
