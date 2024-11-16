<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScriptLogTrigger extends Model
{
    use HasFactory;

    protected $fillable = [
        'script_id',
        'name',
        'message',
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo<UserScript, ScriptLogTrigger>
     */
    public function script(): BelongsTo
    {
        return $this->belongsTo(UserScript::class);
    }
}
