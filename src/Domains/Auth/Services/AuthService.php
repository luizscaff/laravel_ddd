<?php

namespace Domains\Auth\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Domains\User\Repositories\UserRepository;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register($data)
    {
        $validator = Validator::make($data, self::registerRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $userData['name'] = $data['name'];
        $userData['email'] = $data['email'];
        $userData['password'] = Hash::make($data['password']);

        $user = $this->userRepository->create($userData);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    private function registerRules()
    {
        return [
            'name' => 'required|string',
            'email' => 'unique:users,email|required|email|',
            'password' => 'required|string'
        ];
    }

    public function login($data)
    {
        $validator = Validator::make($data, self::loginRules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Unauthorized'], 400);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    private function loginRules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function logout()
    {
        if (auth()->hasUser()) {
            auth()->user()->tokens()->delete();
        }

        return response()->json(['message' => 'You have successfully logged out']);
    }
}
