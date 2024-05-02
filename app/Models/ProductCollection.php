<?php

declare(strict_types=1);

namespace App\Models;

class ProductCollection
{
    private array $products;

    public function __construct(array $products = [])
    {
        $this->products = $products;
    }

    public function add(Product $product): void
    {
        $this->products[] = $product;
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}