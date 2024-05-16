<?php

declare(strict_types=1);

namespace App\Models;

class ProductFurniture extends Product
{
    private string $atributeName = 'dimensions';
    private string $atributeValue;

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->setAtributeValue($data['height']."x".$data['width']."x".$data['length']);
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