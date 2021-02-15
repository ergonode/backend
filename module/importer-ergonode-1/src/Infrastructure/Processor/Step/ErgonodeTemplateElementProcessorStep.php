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
use Ergonode\Importer\Domain\Command\Import\ImportTemplateElementCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeTemplateElementReader;

class ErgonodeTemplateElementProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'templates_elements.csv';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus) {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeTemplateElementReader($directory, self::FILENAME);

        while ($template = $reader->read()) {
            $command = new ImportTemplateElementCommand(
                $import->getId(),
                $template->getName(),
                $template->getType(),
                $template->getX(),
                $template->getY(),
                $template->getWidth(),
                $template->getHeight(),
                $template->getProperty()
            );
            $import->addRecords(1);
            $this->commandBus->dispatch($command, true);
        }
    }
}
