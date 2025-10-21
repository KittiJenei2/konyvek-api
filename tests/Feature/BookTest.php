<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\BookModel;
use App\Models\WriterModel;
use App\Models\User;

class BookTest extends TestCase
{
    use RefreshDatabase;
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    public function test_user_can_view_books_index() 
    {
        $writer = WriterModel::factory()->create();
        BookModel::factory()->count(3)->create(['author_id' => $writer->id]);

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertViewHas('books');
    }

    public function test_authenticated_user_can_create_book()
    {
        $this->actingAs(User::factory()->create());

        $writer = WriterModel::factory()->create();

        $data = [
            'title' => 'New Book',
            'author_id' => $writer->id,
            'price' => 1500,
            'iban' => 'HU1234567890',
            'description' => 'Test description',
            'genre' => 'Novel',
        ];

        $response = $this->post(route('books.store'), $data);

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseHas('books', $data);
        $response->assertSessionHas('succes', 'Book successfully added.');
    }
}
