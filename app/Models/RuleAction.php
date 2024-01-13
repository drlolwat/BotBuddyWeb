<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RuleAction extends Model
{
    use HasFactory;

    protected $fillable = ['rule_id', 'name', 'data', 'order'];

    protected $casts = ['data' => 'array'];

    public function rule(): BelongsTo
    {
        $this->belongsTo(Rule::class);
    }
}
