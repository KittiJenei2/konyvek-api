<?php

namespace Tests\Feature;

use App\Models\WriterModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use NunoMaduro\Collision\Writer;
use Tests\TestCase;

class WriterControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_index_returns_all_writers()
    {
        WriterModel::factory()->create(['name' => 'Ana Huang', 'portrait_path' => null, 'bio' => 'Valakiangol']);

        $response = $this->getJson('/api/writers');

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Ana Huang', 'portrait_path' => null, 'bio' => 'Valakiangol']);
    }

        
    public function test_index_filters_by_needle()
    {       
        WriterModel::factory()->create(['name' => 'Leiner Laura']);
        WriterModel::factory()->create(['name' => 'Ana Huang']);

        $response = $this->getJson('/api/writers/?needle=bar');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Leiner Laura'])
            ->assertJsonFragment(['name' => 'Ana Huang']);
    }

    public function test_store_creates_new_writer()
    {
		$user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/writers', [
            'name' => 'Kovács Anna',
            'portrait_path' => 'anna.jpg',
            'bio' => 'írkál'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
            'name' => 'Kovács Anna',
            'portrait_path' => 'anna.jpg',
            'bio' => 'írkál'
        ]);
		
        $this->assertDatabaseHas('writers', [
            'name' => 'Kovács Anna',
            'portrait_path' => 'anna.jpg',
            'bio' => 'írkál'
        ]);
    }

    public function test_update_modifies_existing_writer()
    {
        $writer = WriterModel::factory()->create(['name' => 'Ana Huang',
                                                  'portrait_path' => null,
                                                  'bio' => 'Valakiangol']);

        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->patchJson("/api/writers/{$writer->id}",
        ['name' => 'Nagy Katalin',
               'portrait_path' => null,
               'bio' => 'Valakiangol']);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Nagy Katalin',
               'portrait_path' => null,
               'bio' => 'Valakiangol']);

        $this->assertDatabaseHas('writers', ['id' => $writer->id, 'name' => 'Nagy Katalin']);
    }

    public function test_update_returns_404_for_missing_writer()
    {
    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->patchJson('/api/writers/999', [
        'name' => 'Kata Kata'
    ]);

    $response->assertStatus(404)
        ->assertJsonFragment(['message' => 'Not found!']);
    }


    public function test_delete_removes_writer()
{
    $writer = WriterModel::factory()->create();

    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->deleteJson("/api/writers/{$writer->id}");

    $response->assertStatus(410)
        ->assertJsonFragment(['message' => 'Deleted']);

    $this->assertDatabaseMissing('writers', ['id' => $writer->id]);
}

}
