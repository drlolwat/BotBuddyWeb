<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proxy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['host', 'port', 'username', 'password', 'user_id', 'proxy_group_id'];

    /**
     * @return BelongsTo<User, Proxy>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<ProxyGroup, Proxy>

     */
    public function proxy_group(): BelongsTo
    {
        return $this->belongsTo(ProxyGroup::class);
    }

    /**
     * @return HasMany<Account>
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }
}
