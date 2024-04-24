<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Response;
use App\ViewResponse;

class ProductController
{
    public function index(): Response
    {
        return new ViewResponse(
            'index',
            ['product' => ['name' => $_ENV['TEST']]]
        );
    }
}