<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as VerifiableEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property ?Carbon $subscription_expires_at
 */
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
        'subscription_id',
        'subscription_expires_at',
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

    /**
     * @return HasMany<AccountGroup>
     */
    public function account_groups(): HasMany
    {
        return $this->hasMany(AccountGroup::class);
    }

    /**
     * @return HasMany<ProxyGroup>
     */
    public function proxy_groups(): HasMany
    {
        return $this->hasMany(ProxyGroup::class);
    }

    /**
     * @return HasMany<UserScript>
     */
    public function scripts(): HasMany
    {
        return $this->hasMany(UserScript::class);
    }

    /**
     * @return HasMany<Account>
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * @return HasMany<Proxy>
     */
    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class);
    }

    /**
     * @return HasMany<Agent>
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    /**
     * @return HasMany<Workflow>
     */
    public function workflows(): HasMany
    {
        return $this->hasMany(Workflow::class);
    }

    /**
     * @return BelongsTo<Subscription, User>
     */
    public function subscription(): BelongsTo
    {
        // if they have never had a subscription or their subscription has expired
        if (!($this->subscription_expires_at && $this->subscription_expires_at->isFuture())) {
            return $this->belongsTo(Subscription::class)->where('id', -1);
        }
        return $this->belongsTo(Subscription::class);
    }

    /**
     * @return HasMany<Notification>
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
