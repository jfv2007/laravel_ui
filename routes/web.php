<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*mandamos la uid , controlador y por ultimo el metodo-> y al final le pasa el nombre*/
Route::get('books/{uuid}/download', [BookController::class, 'download'])->name('books.download');

/*para que pida el id del que se logea*/
Route::resource('books', BookController::class)->middleware('auth');
