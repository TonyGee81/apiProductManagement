<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'show_categories', methods: ['GET'])]
    public function getCategories(
        Request $request,
        CategoryRepository $categoryRepository,
        paginationDTO $paginationDTO,
    ): JsonResponse {
        $categories = $categoryRepository->paginateCategories($paginationDTO->page);
        $groups = $request->query->all('groups');

        return $this->json($categories, 200, [], ['groups' => $groups]);
    }

    #[Route('/categories/{categoryId}', name: 'show_category', methods: ['GET'])]
    public function getCategory(
        Category $categoryId,
        Request $request,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        $category = $categoryRepository->find($categoryId);
        $groups = $request->query->all('groups');

        return $this->json($category, 200, [], ['groups' => $groups]);
    }
}
