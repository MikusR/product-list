<?php

declare(strict_types=1);

namespace App;

class App
{
    public function run(): void
    {
        $config = new Configuration();
        $config->prepareSecrets();
        echo $_ENV['TEST'].PHP_EOL;
    }
}