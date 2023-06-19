<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thesis>
 */
class ThesisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    { 
        return [
            'thesis_text'	 => $this->faker->paragraph,
            'starting_page'	=> strval(rand(1, 100)),
            'ending_page'	=>  strval(rand( 100,  1000)),
            'reviews'	=> $this->faker->randomElement([$this->faker->sentence(rand(2,3)), null]),
            'status'	=> $this->faker->randomElement([null , 'audit', 'review', 'audited', 'rejected']),
            'degree'	=> rand(0, 100),
            'reviewer_id' => 1,
            'auditor_id' => 1,
            'user_book_id'=> 1,
            
        ];
    }
}

