<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'web']], function () {

    Route::post('books/subAuthor', [BookController::class, 'addSubAuthor'])->name('books.subAuthor.store');

    Route::resource('books', BookController::class)->except('show');

});

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
