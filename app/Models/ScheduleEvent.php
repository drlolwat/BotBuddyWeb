<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $start
 * @property int $duration
 */
class ScheduleEvent extends Model
{
    protected $fillable = [
        'account_group_id',
        'name',
        'color',
        'day',
        'script_id',
        'script_params',
        'data',
        'start_at',
        'finish_at',
    ];

    protected $casts = [
        'data' => 'array',
        'start_at' => 'datetime:H:i',
        'finish_at' => 'datetime:H:i',
    ];

    /**
     * @return BelongsTo<AccountGroup, ScheduleEvent>
     */
    public function account_group(): BelongsTo
    {
        return $this->belongsTo(AccountGroup::class);
    }

    /**
     * @return BelongsTo<UserScript, ScheduleEvent>
     */
    public function script(): BelongsTo
    {
        return $this->belongsTo(UserScript::class);
    }
}
