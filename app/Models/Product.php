<?php

declare(strict_types=1);

namespace App\Models;

class Product
{
    private string $sku;
    private string $name;
    private int $price;
    private array $atributes;

    public function __construct(string $sku, string $name, int $price, array $atributes)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->atributes = $atributes;
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

    public function getAtributes(): array
    {
        return $this->atributes;
    }
}
