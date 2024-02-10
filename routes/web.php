<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index']);
Route::get('logout', [LoginController::class,'logout']);

Route::prefix("/admin")->group(function () {
    Route::get('/', [UserController::class, 'index'])->name("users.index");
    Route::get('/user-all', [UserController::class, 'getUsers'])->name("users.all");
    Route::post('/user-trash', [UserController::class, 'trashUser'])->name("users.destroy");
    Route::get('/user-create', [UserController::class, 'createUserView'])->name("users.createView");
    Route::post('/user-create', [UserController::class, 'createUser'])->name("users.create");
    Route::get('/user-update/{id}', [UserController::class, 'updateUserView'])->name("users.updateView");
    Route::post('/user-update', [UserController::class, 'updateUser'])->name("users.update");
    Route::get('/users-trashed', [UserController::class, 'getTrashedUsers'])->name("users.updateView");
    Route::get('/trashed-users', [UserController::class, 'trashedView'])->name("users.trashedView");
    Route::post('/user-delete-permently', [UserController::class, 'deleteUserPermently'])->name("users.deletePermently");
    Route::post('/restore-user', [UserController::class, 'restoreUser'])->name("users.restoreUser");

    Route::softDeletes('users', 'App\Http\Controllers\UserController');

})->middleware("auth");
