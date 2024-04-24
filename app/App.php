<?php

declare(strict_types=1);

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    public function run(): void
    {
        $config = new Configuration();
        $config->prepareSecrets();
        $loader = new FilesystemLoader(__DIR__.'/../resources/views/');
        $twig = new Environment($loader, ['debug' => true,]);

        echo $twig->render('index'.'.twig', ['product' => ['name' => $_ENV['TEST']]]);
    }
}