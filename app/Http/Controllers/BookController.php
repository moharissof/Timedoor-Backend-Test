<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{

    public function index(Request $request)
    {
        // Mengquery manual untuk lebih ter optimalisasi
        $query = Book::with(['author', 'category'])
            ->leftJoin('ratings', 'books.id', '=', 'ratings.book_id')
            ->select([
                'books.*',
                DB::raw('AVG(ratings.rating) as avg_rating'),
                DB::raw('COUNT(ratings.id) as ratings_count')
            ])
            ->groupBy('books.id', 'books.title', 'books.author_id', 'books.category_id', 'books.created_at', 'books.updated_at');

        // Fungsi Pencarian 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('books.title', 'like', "%{$search}%")
                  ->orWhereHas('author', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $query->orderByDesc('avg_rating')
              ->orderByDesc('ratings_count');

        $perPage = in_array($request->per_page, [10,20,30,40,50,60,70,80,90,100]) 
                  ? $request->per_page : 10;

                $books = $query->paginate($perPage)->appends(request()->query());

        return view('books.index', compact('books'));
    }


    public function getBooksByAuthor($authorId)
    {
        $books = Book::where('author_id', $authorId)
                    ->select('id', 'title')
                    ->orderBy('title')
                    ->get();

        return response()->json($books);
    }
}
