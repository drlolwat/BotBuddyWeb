<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Rule extends Model
{
    use HasFactory;

    protected $fillable = ['model_type', 'model_id', 'event', 'data', 'user_id'];

    protected $casts = ['data' => 'array'];

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(RuleAction::class);
    }
}
