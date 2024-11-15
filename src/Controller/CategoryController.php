<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Category;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'show_categories', methods: ['GET'])]
    public function getProducts(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $categories = $entityManager->getRepository(Category::class)->findAll();

        return $this->json($categories);
    }


}
