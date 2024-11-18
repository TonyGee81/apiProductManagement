<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Message\ProductImportMessage;
use App\Repository\SupplierRepository;
use App\Service\GetFileContentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/import', name: 'api_import')]
#[IsGranted('ROLE_ADMIN')]
class ImportController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/csv', name: 'csv', methods: ['POST'])]
    public function index(
        Request $request,
        GetFileContentService $getFileContentService,
        SupplierRepository $supplierRepository,
        MessageBusInterface $bus,
    ): JsonResponse {
        $supplierData = $request->request->get('supplier');
        /** @var Supplier $supplier */
        $supplier = $supplierRepository->find($supplierData);
        /** @var UploadedFile $file */
        $file = $request->files->get('csv');
        $data = $getFileContentService->getCSVContent($file->getPathname());
        foreach ($data as $row) {
            $bus->dispatch(new ProductImportMessage(
                $row['isEuropean'],
                $row['country'],
                $row['name'],
                $row['category'],
                $row['description'],
                $row['code'],
                $row['price'],
                $supplier->getId())
            );
        }

        return $this->json([
            'supplier' => $supplier->getName(),
            'data' => [
                'total' => count($data),
            ],
        ]);
    }
}
