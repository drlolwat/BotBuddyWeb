<?php

declare(strict_types=1);

namespace App\BotBuddy;

enum Status: string
{
    case STARTING = 'Starting';
    case RUNNING = 'Running';
    case STOPPING = 'Stopping';
    case STOPPED = 'Stopped';
    case COMPLETED = 'Completed';
    case BANNED = 'Banned';
    case QUEUED = 'Queued';
    case NO_SCRIPT = 'NoScript';
    case PROXY_BLOCKED = 'ProxyBlocked';
    case LOCKED = 'Locked';
}
