<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowAction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['workflow_id', 'name', 'data', 'order'];

    protected $casts = ['data' => 'array'];

    /**
     * @return BelongsTo<Workflow, WorkflowAction>
     */
    public function rule(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}
