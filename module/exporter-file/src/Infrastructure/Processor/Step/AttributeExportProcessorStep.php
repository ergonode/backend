<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class AttributeExportProcessorStep implements ExportStepProcessInterface
{
    private AttributeQueryInterface $query;

    private CommandBusInterface $commandBus;

    public function __construct(AttributeQueryInterface $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $attributes = $this->query->getDictionary();
        foreach (array_keys($attributes) as $id) {
            $command = new ProcessAttributeCommand($exportId, new AttributeId($id));
            $this->commandBus->dispatch($command, true);
        }
    }
}
