<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
           'name' => rtrim($this->faker->sentence(random_int(1, 5)), '.'),
           'pages' => $this->faker->numberBetween(100, 1000),
           'section_id' => $this->faker->numberBetween(1, 10),
           'level_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}
