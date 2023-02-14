<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'model_class',
        'model_id',
        'version',
        'created_at',
        'created_by',
        'data',
    ];

    protected $casts = [
        'version' => 'integer',
        'created_at' => 'datetime:d.m.Y H:i:s'
    ];
}
