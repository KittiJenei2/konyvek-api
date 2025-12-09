<?php

namespace Database\Factories;

use App\Models\BookModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BookModel>
 */
class BookModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = BookModel::class;
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author_id' => 1, // ezt a teszt felülírja
            'image_path' => null,
            'iban' => $this->faker->isbn13(),
            'price' => $this->faker->randomFloat(2, 1000, 5000),
            'description' => $this->faker->text(50),
            'genre' => $this->faker->word(),
        ];
    }
}
