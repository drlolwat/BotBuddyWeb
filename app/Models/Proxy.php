<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $fillable = ['ip', 'port', 'password', 'user_id', 'proxy_group_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function proxy_group()
    {
        return $this->belongsTo(ProxyGroup::class);
    }
}
