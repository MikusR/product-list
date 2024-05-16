<?php

declare(strict_types=1);

namespace App\Models;

class DVD extends Product
{
    private string $attributeName = 'size';
    private string $attributeValue;

    public function __construct($data)
    {
        parent::__construct($data);
        $size = $data['attributeValue'] ?? $data['size']." MB";
        $this->setAttributeValue($size);
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