<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Models\ProductCollection;
use App\Response;
use App\ViewResponse;

class ProductController
{
    public function index(): Response
    {
        $products = new ProductCollection([
            new Product('123', 'Product 1', 100, [['name' => 'color', 'value' => 'red']]),
            new Product('456', 'Product 2', 200, [['name' => 'color', 'value' => 'blue']]),
            new Product(
                '789', 'Product 3', 300,
                [
                    ['name' => 'weight', 'value' => '10kg'],
                    ['name' => 'height', 'value' => '10cm']
                ]
            ),
            new Product('012', 'Product 4', 400, [
                ['name' => 'weight', 'value' => '20kg'],
                ['name' => 'height', 'value' => '20cm']
            ]),
        ]);
//        var_dump($products->getProducts());
        return new ViewResponse(
            'index',
            ['products' => $products->getProducts()]
        );
    }
}