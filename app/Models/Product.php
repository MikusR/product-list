<?php

declare(strict_types=1);

namespace App\Models;

class Product
{
    private string $sku;
    private string $name;
    private int $price;
    private array $atribute;

    public function __construct(string $sku, string $name, int $price, array $atribute)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->atribute = $atribute;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getAtribute(): array
    {
        return $this->atribute;
    }
}
