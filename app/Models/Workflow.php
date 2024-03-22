<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workflow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'model_type', 'model_id', 'event', 'data', 'user_id'];

    protected $casts = ['data' => 'array'];

    /**
     * @return MorphTo<Model, Workflow>
     */
    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * @return HasMany<WorkflowAction>
     */
    public function actions(): HasMany
    {
        return $this->hasMany(WorkflowAction::class);
    }
}
