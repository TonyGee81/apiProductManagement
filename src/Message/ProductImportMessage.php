<?php

namespace App\Message;

readonly class ProductImportMessage
{
    public function __construct(
        private ?int $isEuropean,
        private ?string $country,
        private string $code,
        private string $description,
        private float $price,
        private string $supplierId,
        private string $name,
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

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSupplierId(): string
    {
        return $this->supplierId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
