<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domains\Auth\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    /**
     * @OA\Post(
     *   path="/api/user/register",
     *   tags={"Register"},
     *   summary="POST for user register",
     *   @OA\Parameter(
     *    description="Name",
     *    in="path",
     *    name="name",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\Parameter(
     *    description="E-mail",
     *    in="path",
     *    name="email",
     *    required=true,
     *    @OA\Schema(type="email"),
     *   ),
     *   @OA\Parameter(
     *    description="Password",
     *    in="path",
     *    name="password",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="email", type="email"),
     *        @OA\Property(property="password", type="string"),
     *        example={
     *         "name": "Darth Vader",
     *         "email": "darth.vader@starwars.com",
     *         "password": "iamyourfather"
     *        }
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Object containing user and bearer token",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *         "user": {
     *           "name": "Darth Vader",
     *           "email": "darth.vader@starwars.com",
     *           "updated_at": "2024-05-20 16:58:00",
     *           "created_at": "2024-05-20 16:58:00",
     *           "id": "4",
     *         },
     *         "token": "4|GiwOBN6ow9qtQMpF3skl8X0ovc61HPuuXTXCzbMM",
     *         "token_type": "Bearer"
     *        }, 
     *        summary="Auth token object"
     *      )
     *    )
     *   )
     * )
     */
    public function register(Request $request)
    {
        return $this->authService->register($request->all());
    }

    /**
     * @OA\Post(
     *   path="/api/user/login",
     *   tags={"Login"},
     *   summary="POST for user login",
     *   @OA\Parameter(
     *    description="E-mail",
     *    in="path",
     *    name="email",
     *    required=true,
     *    @OA\Schema(type="email"),
     *   ),
     *   @OA\Parameter(
     *    description="Password",
     *    in="path",
     *    name="password",
     *    required=true,
     *    @OA\Schema(type="string"),
     *   ),
     *   @OA\RequestBody(
     *    @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *        @OA\Property(property="email", type="email"),
     *        @OA\Property(property="password", type="string"),
     *        example={
     *         "email": "darth.vader@starwars.com",
     *         "password": "iamyourfather"
     *        }
     *      )
     *    )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Object containing user and bearer token",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *         "user": {
     *           "id": "4",
     *           "name": "Darth Vader",
     *           "email": "darth.vader@starwars.com",
     *           "email_verified_at": "",
     *           "created_at": "2024-05-20 16:58:00",
     *           "updated_at": "2024-05-20 16:58:00",
     *         },
     *         "token": "4|GiwOBN6ow9qtQMpF3skl8X0ovc61HPuuXTXCzbMM",
     *         "token_type": "Bearer"
     *        }, 
     *        summary="Auth token object"
     *      )
     *    )
     *   )
     * )
     */
    public function login(Request $request)
    {
        return $this->authService->login($request->all());
    }

    /**
     * @OA\Delete(
     *   path="/api/user/logout",
     *   tags={"Logout"},
     *   summary="DELETE for user logout",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Confirmation message",
     *     @OA\JsonContent(
     *      @OA\Examples(
     *        example="result", 
     *        value={
     *         "message": "You have successfully logged out"
     *        }, 
     *        summary="Logout"
     *      )
     *    )
     *   )
     * )
     */
    public function logout()
    {
        return $this->authService->logout();
    }
}
