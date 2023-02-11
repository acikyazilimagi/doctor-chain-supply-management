<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'count',
    ];

    public function users(){
        return $this->hasMany(User::class, 'referral_link_code', 'code');
    }
}
