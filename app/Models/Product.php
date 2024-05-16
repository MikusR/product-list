<?php

declare(strict_types=1);

namespace App\Models;

abstract class Product
{
    private string $sku;
    private string $name;
    private int $price;
    private string $type;

    public function __construct(array $data)
    {
        $this->setSku($data['sku']);
        $this->setName($data['name']);
        $this->setPrice($data['price']);
        $this->setType($data['type']);
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


}
