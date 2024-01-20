<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountStat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['account_id', 'gp', 'ttl', 'qp', 'skills', 'name', 'world_id', 'type'];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
