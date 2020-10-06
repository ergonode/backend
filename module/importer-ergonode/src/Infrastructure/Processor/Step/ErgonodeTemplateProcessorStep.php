<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ImportTemplateCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeTemplateReader;

/**
 */
final class ErgonodeTemplateProcessorStep implements ErgonodeProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeTemplateReader($directory, 'templates.csv');

        while ($template = $reader->read()) {
            // @todo Add x, y, width and height
            $command = new ImportTemplateCommand(
                $import->getId(),
                $template->getName()
            );
            $this->commandBus->dispatch($command);
        }
    }
}