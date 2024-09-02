<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //factory of arclicle (libelle prix qteStock)
            'libelle' => $this->faker->sentence(5),
            'prix' => $this->faker->randomFloat(2, 1, 100),
            'qteStock' => $this->faker->numberBetween(0, 1),
           

            
        ];
    }
}
