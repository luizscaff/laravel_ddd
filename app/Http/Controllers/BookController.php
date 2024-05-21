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

    /**
      * @OA\Get(
      *   path = "/api/books",
      *   tags = {"Get all books"},
      *   summary = "Returns all books and all the stores they're available at",
      *   security={{"bearerAuth":{}}},
      *   @OA\Response(
      *    response = 200,
      *    description = "OK",
      *    @OA\JsonContent(
      *      @OA\Examples(
      *        example="result", 
      *        value={
      *         "id": 2,
      *         "name": "Harry Potter and the Philosopher's Stone",
      *         "isbn": "1234567890123",
      *         "value": "99.99",
      *         "stores": {
      *           {
      *             "id": 1,
      *             "name": "Some bookstore",
      *             "address": "111 Curitiba Avenue, apt 4B Apucarana PR, 86810280"
      *           },
      *           {
      *             "id": 2,
      *             "name": "Some other bookstore",
      *             "address": "123 Main Street, apt 4B San Diego CA, 91911"
      *           }
      *         }
      *        }, 
      *        summary="List of all books"
      *      )
      *    )
      *   ),
      * )
      */
    public function index()
    {
        return $this->bookService->index();
    }

    /**
      * @OA\Get(
      *   path = "/api/books/{book}",
      *   tags = {"Get book by id"},
      *   summary = "Returns a single book and all the stores it's available at",
      *   security={{"bearerAuth":{}}},
      *   @OA\Parameter(
      *    description="id",
      *    in="path",
      *    name="id",
      *    required=true,
      *    @OA\Schema(type="numeric"),
      *   ),
      *   @OA\Response(
      *    response = 200,
      *    description = "OK",
      *    @OA\JsonContent(
      *      @OA\Examples(
      *        example="result", 
      *        value={
      *         "id": 2,
      *         "name": "Harry Potter and the Philosopher's Stone",
      *         "isbn": "1234567890123",
      *         "value": "99.99",
      *         "stores": {
      *           {
      *            "id": 1,
      *             "name": "Some bookstore",
      *             "address": "111 Curitiba Avenue, apt 4B Apucarana PR, 86810280"
      *           },
      *           {
      *             "id": 2,
      *            "name": "Some other bookstore",
      *             "address": "123 Main Street, apt 4B San Diego CA, 91911"
      *           }
      *         }
      *        }, 
      *        summary="Book object"
      *      )
      *    )
      *   ),
      *   @OA\Response(
      *      response = 404,
      *      description = "Book not found"
      *   )
      * )
      */
    public function show($bookId)
    {
        return $this->bookService->show($bookId);
    }

    /**
     * @OA\Post(
     *   path="/api/books",
     *   tags={"Store a new book"},
     *   summary="POST for book create",
     *   @OA\Parameter(
     *    description="Name",
     *    in="path",
     *    name="name",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\Parameter(
     *    description="ISBN",
     *    in="path",
     *    name="isbn",
     *    required=true,
     *    @OA\Schema(type="numeric"),
     *   ),
     *   @OA\Parameter(
     *    description="Value",
     *    in="path",
     *    name="value",
     *    required=true,
     *    @OA\Schema(type="decimal"),
     *   ),
     *   @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="isbn", type="numeric"),
     *        @OA\Property(property="value", type="decimal"),
     *        example={
     *         "name": "The Lord Of The Rings: The Two Towers",
     *         "isbn": "1112223334445",
     *         "value": 55.55
     *        }
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Object containing newly created book",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *           "name": "The Lord Of The Rings: The Two Towers",
     *           "isbn": "1112223334445",
     *           "value": "55.55",
     *           "created_at": "2024-05-20 16:58:00",
     *           "id": "4",
     *        }, 
     *        summary="Book object"
     *      )
     *    )
     *   )
     * )
     */
    public function store(Request $request)
    {
        return $this->bookService->store($request->all());
    }

    /**
     * @OA\Put(
     *   path="/api/books/{book}",
     *   tags={"Update an existing book"},
     *   summary="PUT for book update",
     *   @OA\Parameter(
     *    description="Name",
     *    in="path",
     *    name="name",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\Parameter(
     *    description="ISBN",
     *    in="path",
     *    name="isbn",
     *    required=true,
     *    @OA\Schema(type="numeric"),
     *   ),
     *   @OA\Parameter(
     *    description="Value",
     *    in="path",
     *    name="value",
     *    required=true,
     *    @OA\Schema(type="decimal"),
     *   ),
     *   @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="isbn", type="numeric"),
     *        @OA\Property(property="value", type="decimal"),
     *        example={
     *         "name": "The Lord Of The Rings: The Two Towers",
     *         "isbn": "1112223334445",
     *         "value": 55.55
     *        }
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Object containing newly created book",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *           "id": "4",
     *           "name": "Darth Vader",
     *           "isbn": "darth.vader@starwars.com",
     *           "value": "55.55",
     *           "updated_at": "2024-05-20 16:58:00",
     *           "created_at": "2024-05-20 16:58:00",
     *           "deleted_at": "2024-05-20 16:58:00",
     *        }, 
     *        summary="Book object"
     *      )
     *    )
     *   )
     * )
     */
    public function update(Request $request, $bookId)
    {
        return $this->bookService->update($request->all(), $bookId);
    }

    /**
     * @OA\Delete(
     *   path="/api/books/{book}",
     *   tags={"Delete an existing book"},
     *   summary="DELETE for book destroy",
     *   @OA\Parameter(
     *    description="ID",
     *    in="path",
     *    name="id",
     *    required=true,
     *    @OA\Schema(type="numeric"),
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Confirmation message",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *           "message": "Book deleted"
     *        }, 
     *        summary="Book delete"
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *      response = 404,
     *      description = "Book not found"
     *   )
     * )
     */
    public function destroy(Request $request, $bookId)
    {
        return $this->bookService->destroy($bookId);
    }
}
