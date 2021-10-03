<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SessionController;
use App\Models\Post;
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

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/login', [SessionController::class, 'create'])->middleware('guest');

Route::get('/auth/twitter', [SessionController::class, 'twitterRedirect'])->name('login.twitter');
Route::get('/auth/twitter/callback', [SessionController::class, 'twitterCallback']);
