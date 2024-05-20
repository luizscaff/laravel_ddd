<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Domains\User\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function login()
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

        return $this->json('post', 'api/user/login', $json);
    }
}
