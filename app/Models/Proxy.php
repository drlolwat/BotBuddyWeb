<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proxy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['host', 'port', 'username', 'password', 'user_id', 'proxy_group_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proxy_group()
    {
        return $this->belongsTo(ProxyGroup::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
