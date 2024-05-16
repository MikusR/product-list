<?php

declare(strict_types=1);

namespace App\Models;

class ProductBook extends Product
{
    private string $atributeName = 'weight';
    private string $atributeValue;

    public function __construct($data)
    {
        parent::__construct($data);
        $weight = $data['atributeValue'] ?? $data['weight']." KG";
        $this->setAtributeValue($weight);
    }

    public function getAtributeValue(): string
    {
        return $this->atributeValue;
    }

    public function setAtributeValue(string $atributeValue): void
    {
        $this->atributeValue = $atributeValue;
    }

    public function getAtributeName(): string
    {
        return $this->atributeName;
    }

    public function setAtributeName(string $atributeName): void
    {
        $this->atributeName = $atributeName;
    }

}