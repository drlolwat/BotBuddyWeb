<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProxyGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'user_id'];

    /**
     * @return HasMany<Proxy>

     */
    public function proxies(): HasMany
    {
        return $this->hasMany(Proxy::class);
    }

    /**
     * @return BelongsTo<User, ProxyGroup>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
