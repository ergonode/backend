<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessOptionCommand;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class OptionExportProcessorStep implements ExportStepProcessInterface
{
    private OptionQueryInterface $query;

    private CommandBusInterface $commandBus;

    public function __construct(OptionQueryInterface $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $options = $this->query->getAll();
        foreach ($options as $option) {
            $command = new ProcessOptionCommand($exportId, new AggregateId($option['id']));
            $this->commandBus->dispatch($command, true);
        }
    }
}
