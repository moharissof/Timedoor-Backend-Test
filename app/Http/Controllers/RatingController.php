<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Show the form for creating a new rating
     */
    public function create()
    {
        $authors = Author::orderBy('name')->get();
        return view('ratings.create', compact('authors'));
    }

    /**
     * Store a newly created rating in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'author_id' => 'required|exists:authors,id',
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:10',
        ]);

        // Verify book belongs to author
        $book = Book::where('id', $request->book_id)
                   ->where('author_id', $request->author_id)
                   ->first();

        if (!$book) {
            return back()->withErrors(['book_id' => 'Selected book does not belong to the selected author.']);
        }

        Rating::create([
            'book_id' => $request->book_id,
            'rating' => $request->rating,
        ]);

        return redirect()->route('books.index')->with('success', 'Rating berhasil ditambahkan!');
    }

    /**
     * Get books by author (AJAX endpoint)
     */
    public function getBooksByAuthor($authorId)
    {
        $books = Book::where('author_id', $authorId)
                    ->select('id', 'title')
                    ->orderBy('title')
                    ->get();

        return response()->json($books);
    }
}
