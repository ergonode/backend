<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductCrossSellingExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class ProductCrossSellingStep implements Shopware6ExportStepProcessInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $crossSellList = $channel->getCrossSelling();
        foreach ($crossSellList as $productCollectionId) {
            $processCommand = new  ProductCrossSellingExportCommand($exportId, $productCollectionId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }
}
