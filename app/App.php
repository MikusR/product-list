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
        session_start();

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = md5(uniqid(random_bytes(3), true));
        }

        $config = new Configuration();
        $config->prepareSecrets();

        $loader = new FilesystemLoader(__DIR__.'/../resources/views/');
        $twig = new Environment($loader, ['debug' => true,]);
        $twig->addExtension(new \Twig\Extension\DebugExtension());

        $twig->addGlobal('csrf_token', $_SESSION['csrf_token']);

        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            $r->addRoute('GET', '/info', [Helper::class, 'info']);
            $r->addRoute('GET', '/migrate', [ProductController::class, 'migrate']);
            $r->addRoute('GET', '/', [ProductController::class, 'index']);
            $r->addRoute('GET', '/add-product', [ProductController::class, 'addProduct']);
            $r->addRoute('POST', '/', [ProductController::class, 'add']);
            $r->addRoute('POST', '/delete', [ProductController::class, 'delete']);
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
                \App\Helper::dump("404e");
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                \App\Helper::dump("405");
                // ... 405 Method Not Allowed
                break;
            case FastRoute\Dispatcher::FOUND:

                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                //split handler into controller and method
                [$controller, $method] = $handler;
                //
                $response = (new $controller())->{$method}(...array_values($vars));
                switch (true) {
                    case $response instanceof ViewResponse:
                        echo $twig->render($response->getViewName().'.twig', $response->getData());
                        break;
                    case $response instanceof RedirectResponse:
                        header('Location: '.$response->getLocation());
                        break;
                    case $response instanceof JsonResponse:
                        header('Content-Type: application/json');
                        echo json_encode($response->getData());
                        break;
                }
                break;
        }
    }
}