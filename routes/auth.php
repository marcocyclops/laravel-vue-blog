<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticationController;

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

Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/login', [AuthenticationController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');