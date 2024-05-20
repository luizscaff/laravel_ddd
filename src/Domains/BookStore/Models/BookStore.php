<?php

namespace Domains\BookStore\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BookStore extends Model
{
    protected $table = 'book_store';

    protected $fillable = [
        'book_id',
        'store_id'
    ];
}
