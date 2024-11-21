<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class CategoryController extends ApiController
{
    private const RESPONSE_404 = 'Category not found';

    #[Route('/categories', name: 'show_categories', methods: ['GET'])]
    public function getCategories(
        Request $request,
        CategoryRepository $categoryRepository,
        #[MapQueryString]
        paginationDTO $paginationDTO,
    ): JsonResponse {
        $categories = $categoryRepository->paginateCategories($paginationDTO->page);
        $groups = $request->query->all('groups');

        return $this->response($categories, $groups);
    }

    #[Route('/categories/{categoryId}', name: 'show_category', methods: ['GET'])]
    public function getCategory(
        int $categoryId,
        Request $request,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        if (!$category = $categoryRepository->find($categoryId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $groups = $request->query->all('groups');

        return $this->response($category, $groups);
    }

    #[Route('/categories/{category}', name: 'edit_category', methods: ['PATCH'])]
    public function edit(
        int $categoryId,
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        if (!$category = $categoryRepository->find($categoryId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');

        $category
            ->setName($content->name);

        $entityManager->flush();

        return $this->response($category, $groups);
    }

    #[Route('/categories', name: 'create_category', methods: ['POST'])]
    public function create(
        EntityManagerInterface $entityManager,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_category'],
            ]
        )]
        Category $category,
    ): JsonResponse {
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->response($category, ['show_categories']);
    }
}
