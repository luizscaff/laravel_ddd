<?php

namespace Domains\Book\Services;

use Illuminate\Support\Facades\Validator;
use Domains\Book\Repositories\BookRepository;
use Domains\Book\Models\Book;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function index()
    {
        $list = $this->bookRepository->index();

        return response()->json($list);
    }

    public function show($bookId)
    {
        $book = $this->bookRepository->getById($bookId);

        if ($book) {
            return response()->json($book);
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
    }

    public function store($data)
    {
        $validator = Validator::make($data, self::saveRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookData['name'] = $data['name'];
        $bookData['isbn'] = $data['isbn'];
        $bookData['value'] = $data['value'];

        $book = $this->bookRepository->store($bookData);

        return response()->json($book);
    }

    public function update($data, $bookId)
    {
        $validator = Validator::make($data, self::saveRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $bookData['name'] = $data['name'];
        $bookData['isbn'] = $data['isbn'];
        $bookData['value'] = $data['value'];

        $book = Book::find($bookId);

        if ($book) {
            $updatedBook = $this->bookRepository->update($bookData, $book);
            return response()->json($updatedBook);
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
    }

    private function saveRules()
    {
        return [
            'name' => 'required|string',
            'isbn' => 'required|integer|digits:13',
            'value' => 'required|decimal:2'
        ];
    }

    public function destroy($bookId)
    {
        $book = Book::find($bookId);

        if ($book) {
            $this->bookRepository->destroy($book);
            return response()->json(['message' => 'Book deleted', 200]);
        } else {
            return response()->json(['message' => 'Book not found'], 404);
        }
    }
}
