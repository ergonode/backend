<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\ProductCrossSellingExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class ProductCrossSellingStep implements ExportStepProcessInterface
{
    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $exportRepository;

    public function __construct(CommandBusInterface $commandBus, ExportRepositoryInterface $exportRepository)
    {
        $this->commandBus = $commandBus;
        $this->exportRepository = $exportRepository;
    }

    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $crossSellList = $channel->getCrossSelling();
        foreach ($crossSellList as $productCollectionId) {
            $lineId = ExportLineId::generate();
            $processCommand = new  ProductCrossSellingExportCommand($lineId, $exportId, $productCollectionId);
            $this->exportRepository->addLine($lineId, $exportId, $productCollectionId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }
}
