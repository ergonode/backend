<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeTemplateElementReader;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeTemplateReader;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

class ErgonodeTemplateProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME_1 = 'templates.csv';
    private const FILENAME_2 = 'templates_elements.csv';

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
        if (!$source->import(ErgonodeZipSource::TEMPLATES)) {
            return;
        }

        $reader1 = new ErgonodeTemplateReader($directory, self::FILENAME_1);
        $reader2 = new ErgonodeTemplateElementReader($directory, self::FILENAME_2);

        while ($template = $reader1->read()) {
            $elements = [];
            $reader2->reset();
            while ($element = $reader2->read()) {
                if ($element->getName() === $template->getName()) {
                    $elements[] = $element->toArray();
                }
            }

            $id = ImportLineId::generate();
            $command = new ImportTemplateCommand(
                $id,
                $import->getId(),
                $template->getName(),
                $elements,
            );

            $this->importRepository->addLine($id, $import->getId(), 'TEMPLATE');
            $this->commandBus->dispatch($command, true);
        }
    }
}
