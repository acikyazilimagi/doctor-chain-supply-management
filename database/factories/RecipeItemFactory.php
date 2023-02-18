<?php

namespace Database\Factories;

use App\Models\RecipeItemCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeItemFactory extends Factory
{
    public function definition()
    {
        $medicines =[
            'İlaç 1',
            'İlaç 2',
            'İlaç 3',
            'Çocuk Doktoru',
            'Kalp Cerrahı',
            'Eldiven',
            'Serum',
        ];
        $categories = RecipeItemCategory::all();

        return [
            'name' => $medicines[array_rand($medicines)],
            'count' => mt_rand(1, 7),
            'category_id' => $categories->random()->id,
        ];
    }
}
