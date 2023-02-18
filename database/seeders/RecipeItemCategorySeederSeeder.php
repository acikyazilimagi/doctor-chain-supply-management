<?php

namespace Database\Seeders;

use App\Models\RecipeItemCategory;
use Illuminate\Database\Seeder;

class RecipeItemCategorySeederSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [ "name" => "İlaç" ],
            [ "name" => "Medikal Ürün" ],
            [ "name" => "Doktor Desteği" ],
            [ "name" => "Diğer" ],
        ];
        RecipeItemCategory::insert($categories);
    }
}
