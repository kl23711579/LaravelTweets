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

Route::get('/', [PostController::class, 'index'])->middleware('auth')->name('home');

Route::get('login', [SessionController::class, 'create'])->middleware('guest')->name('login');

Route::get('/auth/twitter', [SessionController::class, 'twitterRedirect'])->middleware('guest')->name('login.twitter');
Route::get('/auth/twitter/callback', [SessionController::class, 'twitterCallback']);
Route::post('logout', [SessionController::class, 'destroy'])->middleware('auth');
