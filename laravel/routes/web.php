<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;

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

Route::get('/', [HomeController::class,'home'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact')->middleware('can:home.contact');
// Route::get('/secret', [HomeController::class, 'secret'])->name('secret')->middleware('can:home.secret');

// Route::resource('/posts', [PostsController::class])->middleware('auth');
Route::resource('/posts', 'PostsController');
Auth:: routes();
