<?php

use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\NoteController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use OpenApi\Annotations\Get;

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




Route::get('/users', [AuthUserController::class, 'getUserId']);
Route::post('/user/register', [AuthUserController::class, 'register'])->name('api.register');
Route::post('/user/login', [AuthUserController::class, 'login'])->name('api.login');


Route::middleware('auth:api')->group(function () {
    Route::apiResource('/notes', NoteController::class);

    // Route::get('notes', [NoteController::class, 'index'])->name('notes.all');
    // Route::put('update/{note}', [NoteController::class, 'update'])->name('update.note');
    // Route::post('create', [NoteController::class, 'store'])->name('create.note');
    // Route::delete('delete/{note}', [NoteController::class, 'destroy'])->name('delete.note');
    // Route::get('show/{note}', [NoteController::class, 'show'])->name('show.note');

    // Route::get('/notes/user/{id}', [NoteController::class, 'usersNote'])->name('user.all');
});


// Route::apiResource('/notes', NoteController::class);

// Route::get('notes', [NoteController::class, 'index'])->name('notes.all');
// Route::put('update/{note}', [NoteController::class, 'update'])->name('update.note');
// Route::post('create', [NoteController::class, 'store'])->name('create.note');
// Route::delete('delete/{note}', [NoteController::class, 'destroy'])->name('delete.note');
// Route::get('show/{note}', [NoteController::class, 'show'])->name('show.note');

// Route::get('/notes/user/{id}', [NoteController::class, 'usersNote'])->name('user.all');
