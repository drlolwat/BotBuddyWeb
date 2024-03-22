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

    protected $casts = [
        'skills' => 'collection',
    ];

    /**
     * @return BelongsTo<Account, AccountStat>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function getGpFormattedAttribute(): string
    {
        $number = $this->gp;
        if($number >= 1000 && $number < 1000000) {
            return round($number / 1000, 2) . 'K';
        } else if($number >= 1000000 && $number < 1000000000) {
            return round($number / 1000000, 2) . 'M';
        } else if($number >= 1000000000) {
            return round($number / 1000000000, 2) . 'B';
        }

        return (string) $number;
    }
}
