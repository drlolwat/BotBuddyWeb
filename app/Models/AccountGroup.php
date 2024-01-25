<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'user_id', 'script_id', 'script_params', 'world', 'fps'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function script()
    {
        return $this->belongsTo(UserScript::class);
    }
}
