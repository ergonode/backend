<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ImportCategoryCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeCategoryReader;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ErgonodeCategoriesProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'categories.csv';

    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    public function __construct(CommandBusInterface $commandBus, ImportRepositoryInterface $importRepository)
    {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
    }

    public function __invoke(Import $import, ErgonodeZipSource $source, string $directory): void
    {
        if (!$source->import(ErgonodeZipSource::CATEGORIES)) {
            return;
        }

        $reader = new ErgonodeCategoryReader($directory, self::FILENAME);

        while ($category = $reader->read()) {
            $id = ImportLineId::generate();
            $command = new ImportCategoryCommand(
                $id,
                $import->getId(),
                $category->getCode(),
                new TranslatableString($category->getTranslations())
            );
            $this->importRepository->addLine($id, $import->getId(), 'CATEGORY');
            $this->commandBus->dispatch($command, true);
        }
    }
}
