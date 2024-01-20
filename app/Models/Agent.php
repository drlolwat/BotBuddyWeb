<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'user_id', 'uuid', 'agent_key', 'client_type',
        'dreambot_client_path', 'dreambot_scripts_path',
    ];
}
