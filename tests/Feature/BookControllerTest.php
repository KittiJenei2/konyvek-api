<?php

namespace Tests\Feature;

use App\Models\BookModel;
use App\Models\WriterModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_all_books()
    {
        WriterModel::factory()->create(['id' => 1]);
        BookModel::factory()->create([
            'title' => 'Test Book',
            'author_id' => 1
        ]);

        $response = $this->getJson('/api/writers/1/books');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Test Book']);
    }

    public function test_store_creates_new_book()
    {
        $writer = WriterModel::factory()->create();

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->postJson("/api/writers/{$writer->id}/books", [
            'title' => 'New Book',
            'author_id' => $writer->id,
            'image_path' => null,
            'iban' => '1234567890',
            'price' => 2500,
            'description' => 'Test description',
            'genre' => 'Drama'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New Book']);

        $this->assertDatabaseHas('books', [
            'title' => 'New Book'
        ]);
    }

    public function test_update_modifies_existing_book()
    {
        $writer = WriterModel::factory()->create();
        $book = BookModel::factory()->create([
            'title' => 'Old Title',
            'author_id' => $writer->id
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->patchJson("/api/writers/{$writer->id}/books/{$book->id}", [
            'title' => 'New Title'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New Title']);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'New Title'
        ]);
    }

    public function test_update_returns_404_for_missing_book()
    {
        $writer = WriterModel::factory()->create();

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->patchJson("/api/writers/{$writer->id}/books/999", [
            'title' => 'Does not matter'
        ]);

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => 'Not found!']);
    }

    public function test_delete_removes_book()
    {
        $writer = WriterModel::factory()->create();

        $book = BookModel::factory()->create([
            'author_id' => $writer->id
        ]);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->deleteJson("/api/writers/{$writer->id}/books/{$book->id}");

        $response->assertStatus(410)
            ->assertJsonFragment(['message' => 'Deleted']);

        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
