<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Query\ExportQueryInterface;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class ProductExportProcessStep implements ExportStepProcessInterface
{
    private ProductQueryInterface $productQuery;

    private ExportQueryInterface $exportQuery;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $repository;

    public function __construct(
        ProductQueryInterface $productQuery,
        ExportQueryInterface $exportQuery,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $repository
    ) {
        $this->productQuery = $productQuery;
        $this->exportQuery = $exportQuery;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $lastExport = null;
        if (FileExportChannel::EXPORT_INCREMENTAL === $channel->getExportType()) {
            $lastExport = $this->exportQuery->findLastExport($channel->getId());
        }

        foreach ($this->productQuery->getAllEditedIds($lastExport) as $product) {
            $productId = new ProductId($product);
            $lineId = ExportLineId::generate();
            $command = new ProcessProductCommand($lineId, $exportId, $productId);
            $this->repository->addLine($lineId, $exportId, $productId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
