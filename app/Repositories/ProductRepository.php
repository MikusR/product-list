<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductCollection;

interface ProductRepository
{
    public function getAll(): ProductCollection;

    public function save(Product $product): void;

    public function delete(Product $product): void;
}