<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\ExporterFile\Domain\Command\Export\ProcessCategoryCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessProductCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class ProductExportProcessStep implements ExportStepProcessInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $productQuery;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductQueryInterface $productQuery
     * @param CommandBusInterface   $commandBus
     */
    public function __construct(ProductQueryInterface $productQuery, CommandBusInterface $commandBus)
    {
        $this->productQuery = $productQuery;
        $this->commandBus = $commandBus;
    }


    /**
     * @param ExportId $exportId
     */
    public function export(ExportId $exportId): void
    {
        $products = $this->productQuery->getAllIds();
        foreach ($products as $product) {
            $command = new ProcessProductCommand($exportId, new ProductId($product));
            $this->commandBus->dispatch($command, true);
        }
    }
}
