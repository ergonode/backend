<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessTemplateCommand;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class TemplateExportProcessorStep implements ExportStepProcessInterface
{
    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param TemplateQueryInterface $query
     * @param CommandBusInterface    $commandBus
     */
    public function __construct(TemplateQueryInterface $query, CommandBusInterface $commandBus)
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
        $templates = $this->query->getAll();
        foreach ($templates as $template) {
            $command = new ProcessTemplateCommand($exportId, new TemplateId($template));
            $this->commandBus->dispatch($command, true);
        }
    }
}
