<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\Fluent\AssertableJson;
use Domains\Book\Models\Book;
use Domains\BookStore\Models\BookStore;
use Domains\Store\Models\Store;

class BookTest extends TestCase
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
            ->json('get', 'api/books')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    '*' => [
                        'id',
                        'name',
                        'isbn',
                        'value',
                        'stores' => [
                            '*' => [
                                'id',
                                'name',
                                'address'
                            ]
                        ]
                    ]
                ]
            );
    }

    public function test_unauthorized_index()
    {
        $response = $this->json('get', 'api/books')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_successful_get_book_by_id()
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
            ->json('get', 'api/books/' . $book->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'isbn',
                    'value',
                    'stores' => [
                        '*' => [
                            'id',
                            'name',
                            'address'
                        ]
                    ]
                ]
            );
    }

    public function test_unauthorized_get_book_by_id()
    {
        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $response = $this->json('get', 'api/books/' . $book->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_get_book_by_id_using_wrong_id()
    {
        $login = self::login();

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('get', 'api/books/' . PHP_INT_MAX)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Book not found');
            });
    }

    public function test_successful_book_create()
    {
        $login = self::login();

        $data = '{
            "name": "Create Book Test",
            "isbn": "9999999999999",
            "value": 99.99
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('post', 'api/books', $json)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'name',
                    'isbn',
                    'value',
                    'created_at',
                    'id'
                ]
            );

        $response = $this->assertDatabaseHas('books', [
            'name' => 'Create Book Test',
            'isbn' => '9999999999999',
            'value' => 99.99
        ]);
    }

    public function test_book_create_with_validation_errors()
    {
        $login = self::login();

        $data = '{
            "name": "",
            "isbn": "12345",
            "value": "bzzzzz"
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('post', 'api/books', $json)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                "name", "isbn", "value"
            ], "errors");
    }

    public function test_unauthorized_book_create()
    {
        $response = $this->json('get', 'api/books')
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_successful_book_update()
    {
        $login = self::login();

        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $data = '{
            "name": "Update Book Test",
            "isbn": "9999999999999",
            "value": 11.11
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('put', 'api/books/' . $book->id, $json)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'id',
                    'name',
                    'isbn',
                    'value',
                    'updated_at',
                    'created_at',
                    'deleted_at'
                ]
            );

        $response = $this->assertDatabaseMissing('books', [
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $response = $this->assertDatabaseHas('books', [
            'name' => 'Update Book Test',
            'isbn' => '9999999999999',
            'value' => 11.11
        ]);
    }

    public function test_book_update_with_validation_errors()
    {
        $login = self::login();

        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $data = '{
            "name": "",
            "isbn": "9999",
            "value": null
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('put', 'api/books/' . $book->id, $json)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors([
                "name", "isbn", "value"
            ], "errors");
    }

    public function test_unauthorized_book_update()
    {
        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $data = '{
            "name": "Update Book Test",
            "isbn": "9999999999999",
            "value": 11.11
        }';

        $json = json_decode($data, true);

        $response = $this->json('put', 'api/books/' . $book->id, $json)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_book_update_with_wrong_id()
    {
        $login = self::login();

        $data = '{
            "name": "Update Book Test",
            "isbn": "9999999999999",
            "value": 11.11
        }';

        $json = json_decode($data, true);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('put', 'api/books/' . PHP_INT_MAX, $json)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Book not found');
            });
    }

    public function test_successful_book_destroy()
    {
        $login = self::login();

        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $login['token']])
            ->json('delete', 'api/books/' . $book->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Book deleted');
            });

        $response = $this->assertSoftDeleted('books', [
            'id' => $book->id
        ]);
    }

    public function test_unauthorized_book_destroy()
    {
        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $response = $this->json('delete', 'api/books/' . $book->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }

    public function test_book_destroy_with_wrong_id()
    {
        $login = self::login();

        $book = Book::create([
            'name' => 'Book One',
            'isbn' => '1234567890123',
            'value' => '100.00'
        ]);

        $response = $this->json('delete', 'api/books/' . $book->id)
            ->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson(function (AssertableJson $assertableJson) {
                $assertableJson->where('message', 'Unauthenticated.');
            });
    }
}
