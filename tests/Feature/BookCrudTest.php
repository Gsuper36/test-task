<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_books_index()
    {
        $user = User::factory()->create();
        Book::factory(10)->create();

        $response = $this->actingAs($user)
            ->withSession([])
            ->get('/dashboard/books');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_books_create()
    {
        $user = User::factory()->create();
        Author::factory(10)->create();

        $response = $this->actingAs($user)
            ->withSession([])
            ->get('/dashboard/books/create');

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_books_store()
    {
        $user        = User::factory()->create();
        $author      = Author::factory()->create();
        $released_at = Carbon::now()->toDate();
        $payload     = [
            'name'        => 'Book',
            'description' => 'description',
            'released_at' => $released_at,
            'author_id'   => $author->id
        ];

        $response = $this->actingAs($user)
            ->withSession([])
            ->post('/dashboard/books', $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas(
            'book',
            [
                'name'        => 'Book',
                'description' => 'description',
                'released_at' => $released_at,
                'author_id'   => $author->id
            ]
        );

        return Book::authorId($author->id)
            ->first();
    }

    /**
     * @depends test_books_store
     */
    public function test_books_add_subauthor(Book $book)
    {
        $user      = User::factory()->create();
        $subAuthor = Author::factory()->create();

        $response = $this->actingAs($user)
            ->withSession([])
            ->post(
                '/dashboard/books/subAuthor', 
                [
                    'book_id'   => $book->id, 
                    'author_id' => $subAuthor->id
                ]
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas(
            'sub_author',
            [
                'book_id'   => $book->id,
                'author_id' => $subAuthor->id
            ]
        );
    }

    /**
     * @depends test_books_store
     */
    public function test_books_create_view(Book $book)
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withSession([])
            ->get("dashboard/books/{$book->id}/edit");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    /**
     * @depends test_books_store
     */
    public function test_books_update(Book $book)
    {
        $user    = User::factory()->create();
        $payload = [
            'name' => 'New name'
        ];

        $response = $this->actingAs($user)
            ->withSession([])
            ->put("/dashboard/books/{$book->id}", $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas(
            'book',
            [
                'id'   => $book->id,
                'name' => 'New name'
            ]
        );
    }

    /**
     * @depends test_books_store
     */
    public function test_book_delete(Book $book)
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->withSession([])
            ->delete("/dashboard/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing(
            'book',
            [
                'id' => $book->id
            ]
        );
    }
}
