<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;

readonly class ImportCsvService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SlugService $slugService,
    ) {
    }

    public function import(
        ?int $isEuropean,
        ?string $country,
        string $code,
        string $description,
        string $price,
        string $supplierId,
        string $name,
        string $categoryName,
    ): void {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy([
            'code' => $code,
            'supplier' => $supplierId,
        ]);
        $supplier = $this->entityManager->getRepository(Supplier::class)->find($supplierId);

        if (!$product) {
            $product = new Product();
        }

        $europeanProduct = $isEuropean ?? false;
        $productCountry = $country ?? null;

        $slugCategory = $this->slugService->slugify($categoryName);
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['slug' => $slugCategory]);

        if ('' !== $categoryName && !$category) {
            $category = (new Category())->setName($categoryName);
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $category
                ->addProduct($product);
        }

        $product
            ->setIsEuropeanUnion($europeanProduct)
            ->setCountry($productCountry)
            ->setCode($code)
            ->setSupplier($supplier)
            ->setDescription($description)
            ->setPrice($price)
            ->setName($name)
            ->setCategory($category ?? null)
        ;

        $supplier
            ->addProduct($product);

        $this->entityManager->persist($supplier);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }
}
