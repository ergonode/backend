<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterShopware6\Domain\Command\Export\CustomFieldExportCommand;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Processor\Shopware6ExportStepProcessInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

class Shopware6CustomFieldStep implements Shopware6ExportStepProcessInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, Shopware6Channel $channel): void
    {
        $attributeIds = $channel->getCustomField();
        foreach ($attributeIds as $attributeId) {
            $processCommand = new CustomFieldExportCommand($exportId, $attributeId);
            $this->commandBus->dispatch($processCommand, true);
        }
    }
}
