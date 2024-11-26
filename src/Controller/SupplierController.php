<?php

namespace App\Controller;

use App\DTO\paginationDTO;
use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_ADMIN')]
class SupplierController extends ApiController
{
    private const RESPONSE_404 = 'Supplier not found';

    #[Route('/suppliers', name: 'show_suppliers', methods: ['GET'])]
    public function showSuppliers(
        Request $request,
        SupplierRepository $supplierRepository,
        #[MapQueryString]
        paginationDTO $paginationDTO,
    ): JsonResponse {
        $suppliers = $supplierRepository->paginateSuppliers($paginationDTO->page);
        $groups = $request->query->all('groups');

        return $this->response($suppliers, $groups);
    }

    #[Route('/suppliers/{supplierId}', name: 'show_supplier', methods: ['GET'])]
    public function showSupplier(
        int $supplierId,
        Request $request,
        SupplierRepository $supplierRepository,
    ): JsonResponse {
        if (!$supplier = $supplierRepository->find($supplierId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $groups = $request->query->all('groups');

        return $this->response($supplier, $groups);
    }

    #[Route('/suppliers/{supplierId}', name: 'edit_supplier', methods: ['PATCH'])]
    public function edit(
        int $supplierId,
        Request $request,
        EntityManagerInterface $entityManager,
        SupplierRepository $supplierRepository,
    ): JsonResponse {
        if (!$supplier = $supplierRepository->find($supplierId)) {
            return $this->responseNotFound(self::RESPONSE_404);
        }

        $content = json_decode($request->getContent());
        $groups = $request->query->all('groups');

        $supplier
            ->setName($content->name);

        $entityManager->flush();

        return $this->response($supplier, $groups);
    }

    #[Route('/suppliers', name: 'create_supplier', methods: ['POST'])]
    public function create(
        EntityManagerInterface $entityManager,
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['create_supplier'],
            ]
        )]
        Supplier $supplier,
    ): JsonResponse {
        $entityManager->persist($supplier);
        $entityManager->flush();

        return $this->response($supplier, ['show_suppliers']);
    }
}
