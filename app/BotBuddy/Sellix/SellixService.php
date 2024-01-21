<?php

namespace App\BotBuddy\Sellix;

use Sellix\PhpSdk\Sellix;

class SellixService
{
    public Sellix $client;

    public function __construct($apiKey, $shopName)
    {
        $this->client = new Sellix($apiKey, $shopName);
    }
}
