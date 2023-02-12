<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeItem;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        Recipe::truncate();
        RecipeItem::truncate();

        Recipe::factory(200)->create();

        $recipes = Recipe::all();
        foreach ($recipes as $recipe) {
            RecipeItem::factory(mt_rand(1, 15))->create(['recipe_id' => $recipe->id]);
        }
    }
}
