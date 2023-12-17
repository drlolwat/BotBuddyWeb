<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProxyGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];

    public function proxies()
    {
        return $this->hasMany(Proxy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
