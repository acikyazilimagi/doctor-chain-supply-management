<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeItem;
use Epigra\TrGeoZones\Models\CityDistrict;
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
            $city = rand(1, 81);

            $address = CityDistrict::where(['city_id' => $city])->inRandomOrder()->first();

            $recipe->address()->create([
                'city' => $city,
                'district' => $address->ilce,
                'address_detail' => fake()->sentence(),
                'neighbourhood' => $address->id,
                'model_class' => $recipe::class,
            ]);

            RecipeItem::factory(mt_rand(1, 15))->create(['recipe_id' => $recipe->id]);
        }
    }
}
