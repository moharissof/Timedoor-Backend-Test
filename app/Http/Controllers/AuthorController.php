<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthorController extends Controller
{

    public function topAuthors()
    {
        $authors = Author::select([
                'authors.*',
                DB::raw('COUNT(DISTINCT ratings.id) as voters_count')
            ])
            ->join('books', 'books.author_id', '=', 'authors.id')
            ->join('ratings', 'ratings.book_id', '=', 'books.id')
            ->where('ratings.rating', '>', 5)
            ->groupBy('authors.id', 'authors.name', 'authors.created_at', 'authors.updated_at')
            ->orderByDesc('voters_count')
            ->limit(10)
            ->get();

        return view('authors.top', compact('authors'));
    }
}
