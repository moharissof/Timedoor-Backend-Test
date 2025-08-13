<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author_id',
        'category_id',
    ];

    /**
     * Get the author of this book
     */
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the category of this book
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get all ratings for this book
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
