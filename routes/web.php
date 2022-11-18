<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Open\PostController;

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
Route::get('/', function() { return redirect()->route('open.index');})->name('home');

Route::get('/posts', [PostController::class, 'index'])->name('open.index');
Route::get('/posts/list', [PostController::class, 'list'])->name('open.list');

Route::get('/posts/show/{post:slug}', [PostController::class, 'show'])->name('open.show');
