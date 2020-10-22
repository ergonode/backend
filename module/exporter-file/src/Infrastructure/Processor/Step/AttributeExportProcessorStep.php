<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class AttributeExportProcessorStep implements ExportStepProcessInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeQueryInterface $query
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(AttributeQueryInterface $query, CommandBusInterface $commandBus)
    {
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ExportId          $exportId
     * @param FileExportChannel $channel
     */
    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $attributes = $this->query->getDictionary();
        foreach ($attributes as $id => $code) {
            $command = new ProcessAttributeCommand($exportId, new AttributeId($id));
            $this->commandBus->dispatch($command, true);
        }
    }
}
