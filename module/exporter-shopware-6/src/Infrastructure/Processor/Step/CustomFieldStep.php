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
use Ergonode\ExporterShopware6\Domain\Command\Export\CustomFieldExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class CustomFieldStep implements ExportStepProcessInterface
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
        $attributeIds = $channel->getCustomField();
        foreach ($attributeIds as $attributeId) {
            $lineId = ExportLineId::generate();
            $processCommand = new CustomFieldExportCommand($lineId, $exportId, $attributeId);
            $this->exportRepository->addLine($lineId, $exportId, $attributeId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }
}
