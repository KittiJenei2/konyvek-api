<?php

namespace Database\Factories;

use App\Models\WriterModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WriterModel>
 */
class WriterModelFactory extends Factory
{
    use RefreshDatabase;
    protected $model = WriterModel::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
        ];
    }
}

