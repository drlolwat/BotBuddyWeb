<?php

declare(strict_types=1);

namespace App\BotBuddy\Socket\Commands;

abstract class Command
{
    public string $header;

    /**
     * @return array<string, int|string>
     */
    abstract public function dispatchUsing(): array;
}
