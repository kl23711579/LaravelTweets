<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SessionController;
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

Route::get('/', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/', [SessionController::class, 'store'])->middleware('guest');

Route::get('/auth/twitter', [SessionController::class, 'twitterRedirect'])->middleware('guest')->name('login.twitter');
Route::get('/auth/twitter/callback', [SessionController::class, 'twitterCallback']);
Route::post('logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::get('posts', [PostController::class, 'index'])->middleware('auth')->name('home');
Route::Post('posts', [PostController::class, 'store'])->middleware('auth');
