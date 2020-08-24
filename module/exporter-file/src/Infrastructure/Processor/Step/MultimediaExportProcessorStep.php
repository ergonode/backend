<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessMultimediaCommand;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

/**
 */
class MultimediaExportProcessorStep implements ExportStepProcessInterface
{
    /**
     * @var MultimediaQueryInterface
     */
    private MultimediaQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param MultimediaQueryInterface $query
     * @param CommandBusInterface      $commandBus
     */
    public function __construct(MultimediaQueryInterface $query, CommandBusInterface $commandBus)
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
        $multimedia = $this->query->getAll();
        foreach ($multimedia as $id) {
            $command = new ProcessMultimediaCommand($exportId, new MultimediaId($id));
            $this->commandBus->dispatch($command, true);
        }
    }
}
