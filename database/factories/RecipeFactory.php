<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    public function definition()
    {
        $status = mt_rand(0, 1);
        $status_updated_at = $status ? now() : null;

        return [
            'title' => fake()->text(mt_rand(15, 100)),
            'description' => fake()->sentence(mt_rand(0, 6)),
            'created_by' => mt_rand(1,15),
            'status' => $status,
            'status_updated_at' => $status_updated_at
        ];
    }
}
