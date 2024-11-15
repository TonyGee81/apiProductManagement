<?php

namespace App\MessageHandler;

use App\Message\ProductImportMessage;
use App\Service\ImportCsvService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class ProductImportHandler
{
    public function __construct(
        private ImportCsvService $importCsvService,
    ) {
    }

    public function __invoke(ProductImportMessage $message): void
    {
        $this->importCsvService->import($message->getIsEuropean(), $message->getCountry(), $message->getCode(), $message->getDescription(), $message->getPrice(), $message->getSupplierId(), $message->getName(), $message->getCategory());
    }
}
