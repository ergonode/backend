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

/**
 */
class OptionExportProcessorStep implements ExportStepProcessInterface
{
    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param OptionQueryInterface $query
     * @param CommandBusInterface  $commandBus
     */
    public function __construct(OptionQueryInterface $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ExportId $exportId
     */
    public function export(ExportId $exportId): void
    {
        $options = $this->query->getAll();
        foreach ($options as $option) {
            $command = new ProcessOptionCommand($exportId, new AggregateId($option['id']));
            $this->commandBus->dispatch($command, true);
        }
    }
}
