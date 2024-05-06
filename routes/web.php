<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\CheckController;
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

Route::get('/checks',[CheckController::class, 'index'])->name('checks.index');
Route::put('/checks/{id}',[CheckController::class, 'update'])->name('checks.update');
Route::post('/checks/{id}/record',[CheckController::class, 'record'])->name('checks.record');

// Route::post('/todos/store',[TodoController::class, 'store'])->name('todos.store');
//// Route::get('/todos/create',[TodoController::class, 'create'])->name('todos.create');
//// Route::get('/todos/{id}/edit',[TodoController::class,'edit'])->name('todos.edit');

// Route::delete('/todo/{id}',[TodoController::class,'destroy'])->name('todos.destroy');

// Route::resource('todos', TodoController::class);
// Route::post('/todos/recurringAdd',[TodoController::class, 'recurringAdd'])->name('todos.recurringAdd');
Route::resources([
    'todos' => TodoController::class,
    // 'checks' => CheckController::class,
]);
require __DIR__.'/auth.php';
