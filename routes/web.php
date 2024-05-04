<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/todos/todoList',[TodoController::class, 'index'])->name('todos.todoList');
Route::get('/todo/create',[TodoController::class, 'create'])->name('todo.create');
Route::post('/todo/store',[TodoController::class, 'store'])->name('todo.store');
Route::get('/todo/{id}',[TodoController::class,'edit'])->name('todo.edit');
Route::put('/todo/{id}',[TodoController::class, 'update'])->name('todo.update');
Route::delete('/todo/{id}',[TodoController::class,'destroy'])->name('todo.destroy');
require __DIR__.'/auth.php';
