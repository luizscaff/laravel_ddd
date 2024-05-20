<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domains\Book\Services\BookService;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService) {
        $this->bookService = $bookService;
    }

    public function index()
    {
        return $this->bookService->index();
    }

    public function show($bookId)
    {
        return $this->bookService->show($bookId);
    }

    public function store(Request $request)
    {
        return $this->bookService->store($request->all());
    }

    public function update(Request $request, $bookId)
    {
        return $this->bookService->update($request->all(), $bookId);
    }

    public function destroy(Request $request, $bookId)
    {
        return $this->bookService->destroy($bookId);
    }
}
