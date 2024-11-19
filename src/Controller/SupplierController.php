<?php

namespace App\Controller;

use App\Entity\Type;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class SupplierController extends ApiController
{
    #[Route('/types/{typeId}', name: 'edit_type', methods: ['PATCH'])]
    public function edit(
        Type $typeId,
        Request $request,
        EntityManagerInterface $entityManager,
        TypeRepository $typeRepository,
    ): JsonResponse {
        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');

        $type = $typeRepository->find($typeId);
        $type
            ->setName($content->name);

        $entityManager->flush();

        return $this->response($type, $groups);
    }

    #[Route('/types', name: 'create_type', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_type'],
            ]
        )]
        Type $type,
    ): JsonResponse {
        $entityManager->persist($type);
        $entityManager->flush();

        return $this->response($type, ['show_types']);
    }
}