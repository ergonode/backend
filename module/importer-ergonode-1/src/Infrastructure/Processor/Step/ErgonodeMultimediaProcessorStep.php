<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;
use Ergonode\Importer\Domain\Command\Import\ImportMultimediaFromWebCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeMultimediaReader;

class ErgonodeMultimediaProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'multimedia.csv';

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
        if (!$source->import(ErgonodeZipSource::MULTIMEDIA)) {
            return;
        }

        $reader = new ErgonodeMultimediaReader($directory, self::FILENAME);

        while ($media = $reader->read()) {
            $id = ImportLineId::generate();

            $command = new ImportMultimediaFromWebCommand(
                $id,
                $import->getId(),
                $media->getUrl(),
                $media->getName(),
                $media->getAlt()
            );
            $this->importRepository->addLine($id, $import->getId(), 'MULTIMEDIA');
            $this->commandBus->dispatch($command, true);
        }
    }
}
