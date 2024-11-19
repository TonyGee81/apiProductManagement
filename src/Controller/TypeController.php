<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Entity\Type;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class TypeController extends ApiController
{
    #[Route('/suppliers/{supplierId}', name: 'edit_supplier', methods: ['PATCH'])]
    public function edit(
        Supplier $supplierId,
        Request $request,
        EntityManagerInterface $entityManager,
        SupplierRepository $supplierRepository,
    ): JsonResponse {
        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');

        $type = $supplierRepository->find($supplierId);
        $type
            ->setName($content->name);

        $entityManager->flush();

        return $this->response($type, $groups);
    }

    #[Route('/suppliers', name: 'create_supplier', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_supplier'],
            ]
        )]
        Type $type,
    ): JsonResponse {
        $entityManager->persist($type);
        $entityManager->flush();

        return $this->response($type, ['show_suppliers']);
    }
}
