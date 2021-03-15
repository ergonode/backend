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
use Ergonode\ExporterFile\Domain\Command\Export\ProcessTemplateCommand;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class TemplateExportProcessorStep implements ExportStepProcessInterface
{
    private TemplateQueryInterface $query;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $repository;

    public function __construct(
        TemplateQueryInterface $query,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $repository
    ) {
        $this->query = $query;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $templates = $this->query->getAll();
        foreach ($templates as $template) {
            $templateId = new TemplateId($template);
            $lineId = ExportLineId::generate();
            $command = new ProcessTemplateCommand($lineId, $exportId, $templateId);
            $this->repository->addLine($lineId, $exportId, $templateId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
