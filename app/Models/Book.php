<?php

declare(strict_types=1);

namespace App\Models;

class Book extends Product
{
    private string $attributeName = 'weight';
    private string $attributeValue;

    public function __construct($data)
    {
        parent::__construct($data);
        $weight = $data['attributeValue'] ?? $data['weight']." Kg";
        $this->setAttributeValue($weight);
    }

    public function getattributeValue(): string
    {
        return $this->attributeValue;
    }

    public function setAttributeValue(string $attributeValue): void
    {
        $this->attributeValue = $attributeValue;
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function setAttributeName(string $attributeName): void
    {
        $this->attributeName = $attributeName;
    }

}