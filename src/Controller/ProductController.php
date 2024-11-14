<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;


class ProductController extends AbstractController
{
    #[Route('/products', name: 'show_products', methods: ['GET'])]
    public function getProducts(
        Request $request,
        ProductRepository $productRepository,
        #[MapQueryString]
        PaginationDTO $paginationDTO,
    ): JsonResponse
    {
        $products = $productRepository->paginateProducts($paginationDTO->page);
        $groups = $request->query->all('groups');

        return $this->json($products, 200, [], ['groups' => $groups]);
    }

    #[Route('/products/{productId}', name: 'show_product', methods: ['GET'])]
    public function getProduct(
        Product $productId,
        Request $request,
        ProductRepository $productRepository
    ): JsonResponse
    {
        $product = $productRepository->find($productId);
        $groups = $request->query->all('groups');

        return $this->json($product, 200, [], ['groups' => $groups]);
    }

    #[Route('/products/{productId}', name: 'edit_products', methods: ['PATCH'])]
    public function editProduct(
        Product $productId,
        Request $request,
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        TypeRepository $typeRepository
    ): JsonResponse
    {

        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');
        $type= $typeRepository->find($content->type);

        $product = $productRepository->find($productId);
        $product->setType($type);

        $entityManager->flush();

        return $this->json($product, 200, [], ['groups' => $groups]);

    }

    #[Route('/products', name: 'create_products', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_product']
            ]
        )]
        Product $product,
        TypeRepository $typeRepository,
        SupplierRepository  $supplierRepository,
    ): JsonResponse
    {
        $content = json_decode($request->getContent());
        $type = $typeRepository->find($content->type);
        $supplier = $supplierRepository->find($content->supplier);

        $product
            ->setType($type)
            ->setSupplier($supplier)
        ;

        $entityManager->persist($product);
        $entityManager->flush();

        return $this->json($product, 200, [], ['groups' => 'show_products']);
    }
}
