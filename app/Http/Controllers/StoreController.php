<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domains\Store\Services\StoreService;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService) {
        $this->storeService = $storeService;
    }

    /**
      * @OA\Get(
      *   path = "/api/stores",
      *   tags = {"Get all stores"},
      *   summary = "Returns all stores and all the books they sell",
      *   security={{"bearerAuth":{}}},
      *   @OA\Response(
      *    response = 200,
      *    description = "OK",
      *    @OA\JsonContent(
      *      @OA\Examples(
      *        example="result", 
      *        value={
      *         "id": 1,
      *         "name": "Some bookstore",
      *         "address": "111 Curitiba Avenue, apt 4B Apucarana PR, 86810280",
      *         "books": {
      *            {
      *              "id": 4,
      *              "name": "A Song of Ice and Fire",
      *              "isbn": "6549873210349",
      *              "value": "299.99",
      *            },
      *            {
      *              "id": 5,
      *              "name": "Harry Potter and the Philosopher's Stone",
      *              "isbn": "1234567890123",
      *              "value": "99.99",
      *            }
      *          }
      *        }, 
      *        summary="List of all stores"
      *      )
      *    )
      *   ),
      * )
      */
    public function index()
    {
        return $this->storeService->index();
    }

    /**
      * @OA\Get(
      *   path = "/api/stores/{store}",
      *   tags = {"Get store by id"},
      *   summary = "Returns a single store and all the books it sells",
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
      *         "id": 1,
      *         "name": "Some bookstore",
      *         "address": "111 Curitiba Avenue, apt 4B Apucarana PR, 86810280",
      *         "books": {
      *            {
      *              "id": 4,
      *              "name": "A Song of Ice and Fire",
      *              "isbn": "6549873210349",
      *              "value": "299.99",
      *            },
      *            {
      *              "id": 5,
      *              "name": "Harry Potter and the Philosopher's Stone",
      *              "isbn": "1234567890123",
      *              "value": "99.99",
      *            }
      *          }
      *        }, 
      *        summary="Store object"
      *      )
      *    )
      *   ),
      *   @OA\Response(
      *      response = 404,
      *      description = "Store not found"
      *   )
      * )
      */
    public function show($storeId)
    {
        return $this->storeService->show($storeId);
    }

    /**
     * @OA\Post(
     *   path="/api/stores",
     *   tags={"Store a new store"},
     *   summary="POST for store create",
     *   @OA\Parameter(
     *    description="Name",
     *    in="path",
     *    name="name",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\Parameter(
     *    description="Address",
     *    in="path",
     *    name="address",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="address", type="string"),
     *        example={
     *         "name": "The Great Bookstore",
     *         "address": "Bennelong Point, Sydney NSW 2000, Australia"
     *        }
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Object containing newly created store",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *           "name": "The Great Bookstore",
     *           "address": "Bennelong Point, Sydney NSW 2000, Australia",
     *           "created_at": "2024-05-20 16:58:00",
     *           "id": "1",
     *        }, 
     *        summary="Store object"
     *      )
     *    )
     *   )
     * )
     */
    public function store(Request $request)
    {
        return $this->storeService->store($request->all());
    }

    /**
     * @OA\Put(
     *   path="/api/stores/{store}",
     *   tags={"Update an existing store"},
     *   summary="PUT for store update",
     *   @OA\Parameter(
     *    description="Name",
     *    in="path",
     *    name="name",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\Parameter(
     *    description="Address",
     *    in="path",
     *    name="address",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="address", type="string"),
     *        example={
     *         "name": "Yet Another Great Bookstore",
     *         "address": "11 Nicholson St, Carlton VIC 3053, Australia"
     *        }
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Object containing newly created store",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *           "id": "1",
     *           "name": "Yet Another Great Bookstore",
     *           "address": "11 Nicholson St, Carlton VIC 3053, Australia",
     *           "updated_at": "2024-05-20 16:58:00",
     *           "created_at": "2024-05-20 16:58:00",
     *           "deleted_at": "2024-05-20 16:58:00",
     *        }, 
     *        summary="Store object"
     *      )
     *    )
     *   )
     * )
     */
    public function update(Request $request, $storeId)
    {
        return $this->storeService->update($request->all(), $storeId);
    }

    /**
     * @OA\Delete(
     *   path="/api/stores/{store}",
     *   tags={"Delete an existing store"},
     *   summary="DELETE for store destroy",
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
     *           "message": "Store deleted"
     *        }, 
     *        summary="Store delete"
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *      response = 404,
     *      description = "Store not found"
     *   )
     * )
     */
    public function destroy(Request $request, $storeId)
    {
        return $this->storeService->destroy($storeId);
    }
}
