<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'password', 'user_id', 'proxy_id', 'script_id', 'account_group_id'];

    public function proxy()
    {
        return $this->belongsTo(Proxy::class);
    }

    public function script()
    {
        return $this->belongsTo(UserScript::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account_group()
    {
        return $this->belongsTo(AccountGroup::class);
    }
}
