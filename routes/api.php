<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum')->name('auth.logout');

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::patch('/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::group(['prefix' => 'company'], function () {
    Route::get('/{id}', [CompanyController::class, 'show'])->name('company.show');
    Route::get('{id}/comments', [CommentController::class, 'index'])
        ->where('id', '\d+')->name('comments.index');
});

Route::prefix('company')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [CompanyController::class, 'create'])->name('company.create');
    Route::patch('/{id}', [CompanyController::class, 'edit'])->name('company.edit');
    Route::delete('/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');
    Route::post('/{id}/comments', [CommentController::class, 'create'])->name('comments.create');
});

Route::prefix('comments')->middleware('auth:sanctum')->group(function () {
    Route::patch('/{comment}', [CommentController::class, 'edit'])->name('comments.edit');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});
