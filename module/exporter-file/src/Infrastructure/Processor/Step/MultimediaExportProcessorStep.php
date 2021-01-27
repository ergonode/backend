<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessMultimediaCommand;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class MultimediaExportProcessorStep implements ExportStepProcessInterface
{
    private MultimediaQueryInterface $query;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $repository;

    public function __construct(
        MultimediaQueryInterface $query,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $repository
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $multimedia = $this->query->getAll();
        foreach ($multimedia as $id) {
            $multimediaId = new MultimediaId($id);
            $command = new ProcessMultimediaCommand($exportId, $multimediaId);
            $this->repository->addLine($exportId, $multimediaId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
