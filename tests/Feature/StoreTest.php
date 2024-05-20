<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Domains\Book\Models\Book;
use Domains\BookStore\Models\BookStore;
use Domains\Store\Models\Store;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    public function test_successful_index()
    {
        $login = self::login();

        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);
        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);
        $bookStore = BookStore::create([
            'book_id' => $book->id,
            'store_id' => $store->id
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('get', 'api/stores')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'address',
                        'books' => [
                            '*' => [
                                'id',
                                'name',
                                'isbn',
                                'value'
                            ]
                        ]
                    ]
                ]
            );
    }

    public function test_unauthorized_index()
    {
        $response = $this->json('get', 'api/stores')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_successful_get_store_by_id()
    {
        $login = self::login();

        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);
        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);
        $bookStore = BookStore::create([
            'book_id' => $book->id,
            'store_id' => $store->id
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('get', 'api/stores/' . $store->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'address',
                    'books' => [
                        '*' => [
                            'id',
                            'name',
                            'isbn',
                            'value'
                        ]
                    ]
                ]
            );
    }

    public function test_unauthorized_get_store_by_id()
    {
        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $response = $this->json('get', 'api/stores/' . $store->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_get_store_by_id_using_wrong_id()
    {
        $login = self::login();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('get', 'api/stores/' . PHP_INT_MAX)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Store not found');
            });
    }

    public function test_successful_store_create()
    {
        $login = self::login();

        $data = '{
            "name": "Create Store Test",
            "address": "Create Store Address Test"
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('post', 'api/stores', $json)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'name',
                    'address',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            );

        $response = $this->assertDatabaseHas('stores', [
            'name' => 'Create Store Test',
            'address' => 'Create Store Address Test'
        ]);
    }

    public function test_store_create_with_validation_errors()
    {
        $login = self::login();

        $data = '{
            "name": "",
            "address": 12345
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('post', 'api/stores', $json)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                "name", "address"
            ], "errors");
    }

    public function test_unauthorized_store_create()
    {
        $response = $this->json('get', 'api/stores')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_successful_store_update()
    {
        $login = self::login();

        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $data = '{
            "name": "Update Store Test",
            "address": "Update Store Address Test"
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('put', 'api/stores/' . $store->id, $json)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'address',
                    'updated_at',
                    'created_at',
                    'deleted_at'
                ]
            );

        $response = $this->assertDatabaseMissing('stores', [
            'name' => 'Create Store Test',
            'address' => 'Create Store Address Test'
        ]);

        $response = $this->assertDatabaseHas('stores', [
            'name' => 'Update Store Test',
            'address' => 'Update Store Address Test'
        ]);
    }

    public function test_store_update_with_validation_errors()
    {
        $login = self::login();

        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $data = '{
            "name": 12345,
            "address": null
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('put', 'api/stores/' . $store->id, $json)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                "name", "address"
            ], "errors");
    }

    public function test_unauthorized_store_update()
    {
        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $data = '{
            "name": 12345,
            "address": null
        }';

        $json = json_decode($data, true);

        $response = $this->json('put', 'api/stores/' . $store->id, $json)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_store_update_with_wrong_id()
    {
        $login = self::login();

        $data = '{
            "name": "Update Store Test",
            "address": "Update Store Address Test"
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('put', 'api/stores/' . PHP_INT_MAX, $json)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Store not found');
            });
    }

    public function test_successful_store_destroy()
    {
        $login = self::login();

        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('delete', 'api/stores/' . $store->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Store deleted');
            });

        $response = $this->assertSoftDeleted('stores', [
            'id' => $store->id
        ]);
    }

    public function test_unauthorized_store_destroy()
    {
        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $response = $this->json('delete', 'api/stores/' . $store->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_store_destroy_with_wrong_id()
    {
        $login = self::login();

        $store = Store::create([
            'name' => 'Store One',
            'address' => '123 Main Street, apt 4A San Diego CA, 91911'
        ]);

        $response = $this->json('delete', 'api/stores/' . $store->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }
}
