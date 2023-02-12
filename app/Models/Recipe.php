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
    ];

    public function items()
    {
        return $this->hasMany(RecipeItem::class);
    }

    public function address()
    {
        return $this
            ->hasOne(Address::class, 'model_id', 'id')
            ->where(['model_class' => self::class])
            ->with([
                'city',
                'neighbourhood'
            ])
        ;
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
