<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'supported',
        'pharmacy_note'
    ];

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            if (app()->runningInConsole()) {
                $query->created_by = mt_rand(1,15);
            }else{
                $query->created_by = auth()->user()->id;
            }
        });
    }
}
