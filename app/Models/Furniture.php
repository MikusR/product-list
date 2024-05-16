<?php

declare(strict_types=1);

namespace App\Models;

class Furniture extends Product
{
    private string $attributeName = 'dimensions';
    private string $attributeValue;

    public function __construct($data)
    {
        parent::__construct($data);
        $dimensions = $data['attributeValue'] ?? $data['height']."x".$data['width']."x".$data['length'];
        $this->setAttributeValue($dimensions);
    }

    public function getAttributeValue(): string
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