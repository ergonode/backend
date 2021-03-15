<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Infrastructure\Processor\Step;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\ExporterFile\Domain\Command\Export\ProcessCategoryCommand;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;

class CategoryExportProcessorStep implements ExportStepProcessInterface
{
    private CategoryQueryInterface $categoryQuery;

    private CommandBusInterface $commandBus;

    private ExportRepositoryInterface $repository;

    public function __construct(
        CategoryQueryInterface $categoryQuery,
        CommandBusInterface $commandBus,
        ExportRepositoryInterface $repository
    ) {
        $this->categoryQuery = $categoryQuery;
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function export(ExportId $exportId, FileExportChannel $channel): void
    {
        $categories = $this->categoryQuery->getAll(new Language('en_GB'));
        foreach ($categories as $category) {
            $categoryId =  new CategoryId($category['id']);
            $lineId = ExportLineId::generate();
            $command = new ProcessCategoryCommand($lineId, $exportId, $categoryId);
            $this->repository->addLine($lineId, $exportId, $categoryId);
            $this->commandBus->dispatch($command, true);
        }
    }
}
