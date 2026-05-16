<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'publisher',
        'author',
        'genre',
        'publication_date',
        'word_count',
        'price_usd',
    ];

    protected function casts(): array
    {
        return [
            'publication_date' => 'date',
            'word_count' => 'integer',
            'price_usd' => 'decimal:2',
        ];
    }
}
