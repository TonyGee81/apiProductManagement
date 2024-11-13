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
    #[Route('/products', name: 'show_products', methods: ['GET'])]
    public function getProducts(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->paginateProducts($request->query->getInt('page', 1));
        $groups = $request->query->all('groups');

        return $this->json($products, 200, [], ['groups' => $groups]);
    }

    #[Route('/products/{productId}', name: 'show_product', methods: ['GET'])]
    public function getProduct(Product $productId, Request $request, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($productId);
        $groups = $request->query->all('groups');

        return $this->json($product, 200, [], ['groups' => $groups]);
    }

    #[Route('/product/{productId}', name: 'edit_product', methods: ['PATCH'])]
    public function editProduct(Product $productId, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, TypeRepository $typeRepository): JsonResponse
    {

        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');
        $type= $typeRepository->find($content->type);

        $product = $productRepository->find($productId);
        $product->setType($type);

        $entityManager->flush();

        return $this->json($product, 200, [], ['groups' => $groups]);

    }
}
