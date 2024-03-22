<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'user_id', 'uuid', 'agent_key', 'client_type',
        'dreambot_client_path', 'dreambot_scripts_path', 'last_agentdata_at',
        'dreambot_min_heap', 'dreambot_max_heap',
    ];

    public $casts = [
        'last_agentdata_at' => 'datetime'
    ];

    /**
     * @return BelongsTo<User, Agent>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
