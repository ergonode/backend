<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ImportCategoryCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeCategoryReader;

class ErgonodeCategoriesProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'categories.csv';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeCategoryReader($directory, self::FILENAME);

        while ($category = $reader->read()) {
            $command = new ImportCategoryCommand(
                $import->getId(),
                new CategoryCode($category->getCode()),
                new TranslatableString($category->getTranslations())
            );
            $this->commandBus->dispatch($command);
        }
    }
}
