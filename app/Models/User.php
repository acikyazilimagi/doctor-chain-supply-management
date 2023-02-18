<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\PasswordResetLinkNotification;
use App\Traits\HistoryTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HistoryTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'specialty',
        'verified',
        'legal_text',
        'kvkk_text',
        'referral_link_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
    ];

    public function referral_lins(){
        return $this->hasMany(ReferralLink::class, 'user_id', 'id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified' => 'boolean',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->attributes['first_name']  . ' ' . $this->attributes['last_name'];
    }

    public function specialty(){
        return $this->hasOne(Specialty::class, 'id', 'specialty');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetLinkNotification($token));
    }
}
