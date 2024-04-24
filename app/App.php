<?php

declare(strict_types=1);

namespace App;

use App\Controllers\ProductController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use FastRoute;

class App
{
    public function run(): void
    {
        $config = new Configuration();
        $config->prepareSecrets();

        $loader = new FilesystemLoader(__DIR__.'/../resources/views/');
        $twig = new Environment($loader, ['debug' => true,]);

        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/', [ProductController::class, 'index']);
        });

        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // ... 404 Not Found
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                // ... call $handler with $vars
                [$controller, $method] = $handler;
                $response = ($controller)->{$method}(...array_values($vars));
                switch (true) {
                    case $response instanceof ViewResponse:
                        echo $twig->render($response->getViewName().'.twig', $response->getData());
                        break;
                    case $response instanceof RedirectResponse:
                        header('Location: '.$response->getLocation());
                        break;
                }
                break;
        }
    }
}