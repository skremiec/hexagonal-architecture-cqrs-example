<?php

use App\Http\Controllers\AddCommentController;
use App\Http\Controllers\CreatePostController;
use App\Http\Controllers\ListPostsController;
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

Route::get('/', fn () => redirect()->route('posts.list'));

Route::get('/posts', ListPostsController::class)->name('posts.list');
Route::post('/posts', CreatePostController::class)->name('posts.create');
Route::post('/posts/{title}/comments', AddCommentController::class)->name('comment.add');
