<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => fake()->text(mt_rand(15, 100)),
            'description' => fake()->sentence(mt_rand(0, 6)),
            'created_by' => mt_rand(1,15),
            'status' => mt_rand(0,1),
        ];
    }
}
