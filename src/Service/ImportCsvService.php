<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

readonly class ImportCsvService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function import(?int $isEuropean, ?string $country, string $code, string $description, string $price, string $supplierId, string $name): void
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(['code' => $code, 'supplier' => $supplierId]);
        $supplier = $this->entityManager->getRepository(Supplier::class)->find($supplierId);

        if (!$product) {
            $product = new Product();
        }

        $europeanProduct = $isEuropean ?? false;
        $productCountry = $country ?? null;
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($name .$description);

        $product
            ->setIsEuropeanUnion($europeanProduct)
            ->setCountry($productCountry)
            ->setCode($code)
            ->setSupplier($supplier)
            ->setDescription($description)
            ->setPrice($price)
            ->setName($name)
            ->setSlug($slug)
        ;

        $supplier
            ->addProduct($product);

        $this->entityManager->persist($supplier);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
