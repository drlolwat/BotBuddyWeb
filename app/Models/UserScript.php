<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserScript extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'user_id', 'script'];

    /**
     * @return BelongsTo<User, UserScript>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<ScriptLogTrigger>
     */
    public function log_triggers(): HasMany
    {
        return $this->hasMany(ScriptLogTrigger::class, 'script_id');
    }
}
