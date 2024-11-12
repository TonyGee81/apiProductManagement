<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;


class ProductController extends AbstractController
{

    public function __construct(
        private SerializerInterface $serializer
    )
    {
    }

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function getProducts(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAll();
        $json = $this->serializer->serialize($products, 'json', ['groups' => ['show_product']]);

        return $this->json([
            'products' => $json,
        ]);
    }

    #[Route('/product/{productId}', name: 'product_edit', methods: ['PATCH'])]
    public function editProduct(Product $productId, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, TypeRepository $typeRepository): JsonResponse
    {

        $content = json_decode($request->getContent());
        $type= $typeRepository->find($content->type);

        $product = $productRepository->find($productId);
        $product->setType($type);

        $entityManager->flush();

        $json = $this->serializer->serialize($product, 'json', ['groups' => ['show_edit_product']]);

        return $this->json([
            'description' => $product->getDescription(),
            'type' => $product->getType()->getName(),
        ]);
    }
}
