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
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeOptionReader;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

class ErgonodeOptionsProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'options.csv';

    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        ImportRepositoryInterface $importRepository
    ) {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
    }


    public function __invoke(Import $import, ErgonodeZipSource $source, string $directory): void
    {
        if (!$source->import(ErgonodeZipSource::OPTIONS)) {
            return;
        }

        $reader = new ErgonodeOptionReader($directory, self::FILENAME);

        while ($option = $reader->read()) {
            $id = ImportLineId::generate();
            $command = new ImportOptionCommand(
                $id,
                $import->getId(),
                $option->getAttribute(),
                $option->getCode(),
                new TranslatableString($option->getTranslations())
            );
            $this->importRepository->addLine($id, $import->getId(), 'OPTION');
            $this->commandBus->dispatch($command, true);
        }
    }
}
