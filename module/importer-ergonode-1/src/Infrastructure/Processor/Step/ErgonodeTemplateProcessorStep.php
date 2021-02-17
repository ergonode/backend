<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeTemplateReader;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;
use Ergonode\Importer\Domain\Repository\ImportRepositoryInterface;

class ErgonodeTemplateProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'templates.csv';

    private CommandBusInterface $commandBus;

    private ImportRepositoryInterface $importRepository;

    public function __construct(
        CommandBusInterface $commandBus,
        ImportRepositoryInterface $importRepository
    ) {
        $this->commandBus = $commandBus;
        $this->importRepository = $importRepository;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeTemplateReader($directory, self::FILENAME);

        while ($template = $reader->read()) {
            $id = ImportLineId::generate();
            $command = new ImportTemplateCommand(
                $id,
                $import->getId(),
                $template->getName(),
                $template->getType(),
                $template->getX(),
                $template->getY(),
                $template->getWidth(),
                $template->getHeight(),
                $template->getProperty()
            );
            $this->importRepository->addLine($id, $import->getId(), 'TEMPLATE');
            $this->commandBus->dispatch($command, true);
        }
    }
}
