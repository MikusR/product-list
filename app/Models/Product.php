<?php

declare(strict_types=1);

namespace App\Models;

class Product
{
    private string $sku;
    private string $name;
    private int $price;
    private string $type;
    private array $atributes;

    public function __construct(string $sku, string $name, int $price, string $type, array $atributes)
    {
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
        $this->setType($type);
        $this->setAtributes($atributes);
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAtributes(): array
    {
        return $this->atributes;
    }

    public function setAtributes(array $atributes): void
    {
        $this->atributes = $atributes;
    }
}
