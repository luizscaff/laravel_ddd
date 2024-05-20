<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Domains\User\Models\User;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_successful_user_registration()
    {    
        $data = '{
            "email": "user@test",
            "name": "Test User",
            "password": "12345678"
        }';
    
        $json = json_decode($data, true);
    
        $response = $this->json('post', 'api/user/register', $json)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'user' => [
                        'name',
                        'email',
                        'updated_at',
                        'created_at',
                        'id'
                    ],
                    'token',
                    'token_type'
                ]
            );

        $response = $this->assertDatabaseHas('users', [
            'email' => 'user@test',
            'name' => 'Test User'
        ]);
    }

    public function test_user_registration_with_validation_errors()
    {
        $data = '{
            "email": "usertest",
            "name": "",
            "password": 12345678
        }';

        $json = json_decode($data, true);

        $response = $this->json('post', 'api/user/register', $json)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                "name", "email", "password"
            ], "errors");
    }

    public function test_user_successful_login()
    {
        $user = User::create([
          'name' => 'Login Test',
          'email' => 'login@test',
          'password' => '$2y$10$AuY4vtPz.sx8rE4TU14IZuLT3yPkGJOdo/kkM0sIJx1N84bqWApIa'
        ]);

        $data = '{
            "email": "login@test",
            "password": "12345678"
        }';
    
        $json = json_decode($data, true);
    
        $response = $this->json('post', 'api/user/login', $json)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'created_at',
                        'updated_at'
                    ],
                    'token',
                    'token_type'
                ]
            );
    }

    public function test_user_login_with_wrong_credentials()
    {
        $user = User::create([
          'name' => 'Login Test',
          'email' => 'login@test',
          'password' => '$2y$10$AuY4vtPz.sx8rE4TU14IZuLT3yPkGJOdo/kkM0sIJx1N84bqWApIa'
        ]);

        $data = '{
            "email": "login@test",
            "password": "abcdefghij"
        }';

        $json = json_decode($data, true);

        $response = $this->json('post', 'api/user/login', $json)
            ->assertStatus(Response::HTTP_FORBIDDEN)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthorized');
            });

    }

    public function test_user_successful_logout()
    {
        $login = self::login();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('delete', 'api/user/logout')
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'You have successfully logged out');
            });

    }

    public function test_user_unauthorized_logout_attempt()
    {
        $login = self::login();

        $response = $this->json('delete', 'api/user/logout')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });

    }
}
