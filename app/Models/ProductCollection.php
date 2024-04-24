<?php

declare(strict_types=1);

namespace App\Models;

class ProductCollection
{
    private $products = [];

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function getProducts()
    {
        return $this->products;
    }
}