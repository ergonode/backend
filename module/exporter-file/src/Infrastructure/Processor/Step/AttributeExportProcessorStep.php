<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessAttributeCommand;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class AttributeExportProcessorStep implements ExportStepProcessInterface
{
    private AttributeQueryInterface $query;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $repository;

    public function __construct(
        AttributeQueryInterface $query,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $repository
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $attributes = $this->query->getDictionary();
        foreach (array_keys($attributes) as $id) {
            $attributeId = new AttributeId($id);
            $lineId = ExportLineId::generate();
            $command = new ProcessAttributeCommand($lineId, $exportId, $attributeId);
            $this->repository->addLine($lineId, $exportId, $attributeId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
