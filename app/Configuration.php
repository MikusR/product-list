<?php

declare(strict_types=1);

namespace App;

use Dotenv\Dotenv;

class Configuration
{
    public function prepareSecrets()
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../');
        $dotenv->safeLoad();
    }
}