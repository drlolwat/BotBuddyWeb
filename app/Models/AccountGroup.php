<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'user_id', 'agent_id', 'script_id',
        'script_params', 'world', 'fps', 'disable_browser_proxy',
    ];

    /**
     * @return BelongsTo<User, AccountGroup>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Account>
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    /**
     * @return BelongsTo<UserScript, AccountGroup>
     */
    public function script(): BelongsTo
    {
        return $this->belongsTo(UserScript::class);
    }

    /**
     * @return BelongsTo<Agent, AccountGroup>
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * @return HasMany<ScheduleEvent>
     */
    public function schedule_events(): HasMany
    {
        return $this->hasMany(ScheduleEvent::class);
    }
}
