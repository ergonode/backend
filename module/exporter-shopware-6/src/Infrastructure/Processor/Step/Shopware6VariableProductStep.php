<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProcessShopware6ExportCommand;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class Shopware6VariableProductStep implements Shopware6ExportStepProcessInterface
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductQueryInterface $query
     * @param CommandBusInterface   $commandBus
     */
    public function __construct(ProductQueryInterface $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ExportId $exportId
     */
    public function export(ExportId $exportId): void
    {
        foreach ($this->query->findProductIdByType(VariableProduct::TYPE) as $product) {
            $productId = new ProductId($product);

            $processCommand = new ProcessShopware6ExportCommand($exportId, $productId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }
}
