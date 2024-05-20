<?php

namespace Domains\Book\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Domains\BookStore\Models\BookStore;
use Domains\Store\Models\Store;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'isbn',
        'value',
    ];

    public function stores()
    {
        return $this->hasManyThrough(
            Store::class,
            BookStore::class,
            'book_id',
            'id',
            'id',
            'store_id'
        );
    }
}
