<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Entity\Type;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class TypeController extends AbstractController
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

        return $this->json($type, 200, [], ['groups' => $groups]);
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

        return $this->json($type, 200, [], ['groups' => 'show_suppliers']);
    }
}
