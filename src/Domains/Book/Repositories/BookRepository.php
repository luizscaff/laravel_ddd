<?php

namespace Domains\Book\Repositories;

use Domains\Book\Models\Book;

class BookRepository
{
    public function index()
    {
        return Book::select('name', 'isbn', 'value')->get();
    }

    public function getById($bookId)
    {
        return Book::select('name', 'isbn', 'value')->find($bookId);
    }

    public function store(array $data)
    {
        return Book::create($data);
    }

    public function update(array $data, Book $book)
    {
        $book->update($data);

        return $book;
    }

    public function destroy(Book $book)
    {
        return $book->delete();
    }
} 