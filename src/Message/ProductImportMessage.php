<?php

namespace App\Message;

class ProductImportMessage
{
    public function __construct(
        private readonly string $code,
        private readonly string $description,
        private readonly string $price,
        private readonly string $supplierId,
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getSupplierId(): string
    {
        return $this->supplierId;
    }
}
