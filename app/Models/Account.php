<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email', 'password', 'user_id', 'proxy_id', 'script_id',
        'account_group_id', 'agent_id', 'status',
        'script_params', 'password_2fa', 'temp_banned_at','perm_banned_at',
        'world', 'fps', 'start_queued_at', 'last_started_at', 'bank_pin',
    ];

    /**
     * @return BelongsTo<Proxy, Account>
     */
    public function proxy(): BelongsTo
    {
        return $this->belongsTo(Proxy::class);
    }

    /**
     * @return BelongsTo<UserScript, Account>
     */
    public function script(): BelongsTo
    {
        return $this->belongsTo(UserScript::class);
    }

    /**
     * @return BelongsTo<User, Account>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<AccountGroup, Account>
     */
    public function account_group(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }

    /**
     * @return BelongsTo<Agent, Account>
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * @return HasOne<AccountStat>
     */
    public function stats(): HasOne
    {
        return $this->hasOne(AccountStat::class);
    }
}
