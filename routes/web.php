<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\RatingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to books list
Route::get('/', function () {
    return redirect()->route('books.index');
});

// Books routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');

// Authors routes
Route::get('/authors/top', [AuthorController::class, 'topAuthors'])->name('authors.top');

// Ratings routes
Route::get('/ratings/create', [RatingController::class, 'create'])->name('ratings.create');
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

// AJAX routes
Route::get('/api/authors/{author}/books', [RatingController::class, 'getBooksByAuthor'])->name('api.books.by-author');
