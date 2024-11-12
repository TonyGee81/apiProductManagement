<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;

readonly class ImportCsvService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function import(string $code, string $description, string $price, string $supplierId): void
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $code, 'supplier' => $supplierId]);
        $supplier = $this->entityManager->getRepository(Supplier::class)->find($supplierId);

        if (!$product) {
            $product = new Product();
        }

        $product
            ->setCode($code)
            ->setSupplier($supplier)
            ->setDescription($description)
            ->setPrice($price);

        $supplier
            ->addProduct($product);

        $this->entityManager->persist($supplier);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}