<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/profile', [UserController::class, 'showProfile'])->middleware('auth')->name('profile');
Route::post('updateProfile', [UserController::class, 'updateProfile'])->middleware('auth')->name('updateProfile');
Route::get('/users', [UserController::class, 'showUsers'])->middleware('auth')->name('users');
Route::post('/addFriend', [UserController::class, 'addFriend']);
Route::get('/user/{id}', [UserController::class, 'showProfile']);
Route::get('/chat/{id}', [\App\Http\Controllers\ChatController::class, 'showChat'])->name('showChat');
Route::post('/receivingMessage', [\App\Http\Controllers\ChatController::class, 'receivingMessage']);
Route::post('/getMessage', [\App\Http\Controllers\ChatController::class, 'getMessage']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
