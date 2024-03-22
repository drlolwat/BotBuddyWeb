<?php

namespace App\BotBuddy\Sellix;

use Sellix\PhpSdk\Sellix;

class SellixService
{
    public Sellix $client;

    public function __construct(string $apiKey, ?string $shopName = null)
    {
        $this->client = new Sellix($apiKey, $shopName);
    }
}
