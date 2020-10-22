<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;

class ProductExportProcessStep implements ExportStepProcessInterface
{
    private ProductQueryInterface $productQuery;

    private ExportQueryInterface $exportQuery;

    private CommandBusInterface $commandBus;

    public function __construct(
        ProductQueryInterface $productQuery,
        ExportQueryInterface $exportQuery,
        CommandBusInterface $commandBus
    ) {
        $this->productQuery = $productQuery;
        $this->exportQuery = $exportQuery;
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $products = [];
        if (FileExportChannel::EXPORT_INCREMENTAL === $channel->getExportType()) {
            $lastExport = $this->exportQuery->findLastExport($channel->getId());
            if ($lastExport) {
                $products = $this->productQuery->getAllEditedIds($lastExport);
            }
        } else {
            $products = $this->productQuery->getAllEditedIds();
        }

        foreach ($products as $product) {
            $command = new ProcessProductCommand($exportId, new ProductId($product));
            $this->commandBus->dispatch($command, true);
        }
    }
}
