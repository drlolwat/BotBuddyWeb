<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as VerifiableEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable, VerifiableEmail, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dreambot_username',
        'dreambot_password',
        'sellix_customer_uniqid',
        'subscription_id', 'subscription_expires_at',
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'subscription_expires_at' => 'datetime',
    ];

    public function account_groups()
    {
        return $this->hasMany(AccountGroup::class);
    }

    public function proxy_groups()
    {
        return $this->hasMany(ProxyGroup::class);
    }

    public function scripts()
    {
        return $this->hasMany(UserScript::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function proxies()
    {
        return $this->hasMany(Proxy::class);
    }

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function workflows()
    {
        return $this->hasMany(Workflow::class);
    }

    public function subscription()
    {
        // if they have never had a subscription or their subscription has expired
        if (!($this->subscription_expires_at && $this->subscription_expires_at->isFuture())) {
            return null;
        }
        return $this->belongsTo(Subscription::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
