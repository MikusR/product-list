<?php

declare(strict_types=1);

namespace App\Models;

class ProductDVD extends Product
{
    private string $atributeName = 'size';
    private string $atributeValue;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setAtributeValue($data['size']." MB");
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