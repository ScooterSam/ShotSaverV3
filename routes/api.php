<?php

use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Files\FileUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;

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

Route::middleware('auth:sanctum')->name('api.')->group(function () {

    Route::get('/user', [UserController::class, 'current'])->name('user.current');

    Route::post('/upload', [FileUploadController::class, 'upload']);

    Route::get('files', [FileController::class, 'list']);
    Route::get('files/favourites', [FileController::class, 'favourites']);
    Route::get('files/{file}', [FileController::class, 'file']);

});
