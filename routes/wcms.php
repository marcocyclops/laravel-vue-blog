<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Wcms\PostController;

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
    
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/list', [PostController::class, 'list'])->name('posts.list');

Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

Route::get('/posts/{post:slug}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{post:id}', [PostController::class, 'update'])->name('posts.update');

Route::delete('/posts/{post:id}', [PostController::class, 'destroy'])->name('posts.destroy');
Route::delete('/posts', [PostController::class, 'destroySelected'])->name('posts.destroy.selected');

Route::get('/posts/toggle/{post:id}', [PostController::class, 'toggle'])->name('posts.toggle');
Route::get('/posts/toggle', [PostController::class, 'toggleSelected'])->name('posts.toggle.selected');