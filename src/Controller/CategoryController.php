<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

    #[Route('/categories/{categoryId}', name: 'edit_category', methods: ['PATCH'])]
    public function edit(
        Category $categoryId,
        Request $request,
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
    ): JsonResponse {
        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');

        $category = $categoryRepository->find($categoryId);
        $category
            ->setName($content->name);

        $entityManager->flush();

        return $this->json($category, 200, [], ['groups' => $groups]);
    }

    #[Route('/categories', name: 'create_category', methods: ['POST'])]
    public function create(
        Request $request,
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

        return $this->json($category, 200, [], ['groups' => 'show_categories']);
    }
}
