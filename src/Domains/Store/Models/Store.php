<?php

namespace Domains\Store\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Domains\Book\Models\Book;
use Domains\BookStore\Models\BookStore;

class Store extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address',
    ];

    public function books()
    {
        return $this->hasManyThrough(
            Book::class,
            BookStore::class,
            'store_id',
            'id',
            'id',
            'book_id'
        );
    }
}
