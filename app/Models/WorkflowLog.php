<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowLog extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'workflow_id'];

    /**
     * @return BelongsTo<Workflow, WorkflowLog>
     */
    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    /**
     * @return BelongsTo<Account, WorkflowLog>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
