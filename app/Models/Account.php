<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email', 'password', 'user_id', 'proxy_id', 'script_id',
        'account_group_id', 'agent_id', 'status',
        'script_params', 'password_2fa', 'temp_banned_at','perm_banned_at',
        'world', 'fps', 'start_queued_at',
    ];

    public function proxy()
    {
        return $this->belongsTo(Proxy::class);
    }

    public function script()
    {
        return $this->belongsTo(UserScript::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account_group()
    {
        return $this->belongsTo(AccountGroup::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function stats()
    {
        return $this->hasOne(AccountStat::class);
    }
}
