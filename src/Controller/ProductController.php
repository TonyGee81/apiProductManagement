<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class ProductController extends ApiController
{
    private const RESPONSE_404 = 'Product not found';

    #[Route('/products', name: 'show_products', methods: ['GET'])]
    public function showProducts(
        Request $request,
        ProductRepository $productRepository,
        #[MapQueryString]
        paginationDTO $paginationDTO,
    ): JsonResponse {
        $products = $productRepository->paginateProducts($paginationDTO->page);
        $groups = $request->query->all('groups');

        return $this->response($products, $groups);
    }

    #[Route('/products/{productId}', name: 'show_product', methods: ['GET'])]
    public function showProduct(
        int $productId,
        Request $request,
        ProductRepository $productRepository,
    ): JsonResponse {
        if (!$product = $productRepository->find($productId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $groups = $request->query->all('groups');

        return $this->response($product, $groups);
    }

    #[Route('/products/{productId}', name: 'edit_product', methods: ['PATCH'])]
    public function edit(
        int $productId,
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        SupplierRepository $supplierRepository,
        ProductRepository $productRepository,
    ): JsonResponse {
        if (!$product = $productRepository->find($productId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');
        $category = $categoryRepository->find($content->category);
        $supplier = $supplierRepository->find($content->supplier);

        $product
            ->setSupplier($supplier)
            ->setCode($content->code)
            ->setDescription($content->description)
            ->setPrice($content->price)
            ->setName($content->name)
            ->setCountry($content->country)
            ->setIsEuropeanUnion($content->isEuropean)
            ->setCategory($category)
        ;

        $entityManager->flush();

        return $this->response($product, $groups);
    }

    #[Route('/products', name: 'create_products', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_product'],
            ]
        )]
        Product $product,
        CategoryRepository $categoryRepository,
        SupplierRepository $supplierRepository,
    ): JsonResponse {
        $content = json_decode($request->getContent());
        $category = $categoryRepository->find($content->category);
        $supplier = $supplierRepository->find($content->supplier);

        $product
            ->setCategory($category)
            ->setSupplier($supplier)
        ;

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->response($product, ['show_products']);
    }
}
