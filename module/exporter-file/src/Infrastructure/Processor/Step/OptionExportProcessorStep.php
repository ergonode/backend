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
use Ergonode\ExporterFile\Domain\Command\Export\ProcessOptionCommand;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class OptionExportProcessorStep implements ExportStepProcessInterface
{
    private OptionQueryInterface $query;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $repository;

    public function __construct(
        OptionQueryInterface $query,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $repository
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $options = $this->query->getAll();
        foreach ($options as $option) {
            $optionId = new AggregateId($option['id']);
            $lineId = ExportLineId::generate();
            $command = new ProcessOptionCommand($lineId, $exportId, $optionId);
            $this->repository->addLine($lineId, $exportId, $optionId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
