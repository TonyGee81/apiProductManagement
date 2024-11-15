<?php

namespace App\Message;

readonly class ProductImportMessage
{
    public function __construct(
        private ?int $isEuropean,
        private ?string $country,
        private string $name,
        private string $category,
        private string $description,
        private string $code,
        private float $price,
        private string $supplierId,
    ) {
    }

    public function getIsEuropean(): ?int
    {
        return $this->isEuropean;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSupplierId(): string
    {
        return $this->supplierId;
    }
}
