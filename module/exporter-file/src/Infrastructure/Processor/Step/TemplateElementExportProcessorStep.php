<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessTemplateCommand;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class TemplateElementExportProcessorStep implements ExportStepProcessInterface
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
            $command = new ProcessTemplateCommand($exportId, $templateId);
            $this->repository->addLine($exportId, $templateId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
