<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipe_id',
        'name',
        'count',
        'category_id',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public static function categories(){
        return [
            [
                "id" => 1,
                "value" => "Ilac",
            ],
            [
                "id" => 2,
                "value" => "Medikal Urun",
            ],
            [
                "id" => 3,
                "value" => "Doktor Destegi",
            ],
            [
                "id" => 4,
                "value" => "Diger",
            ],
        ];
    }
}
