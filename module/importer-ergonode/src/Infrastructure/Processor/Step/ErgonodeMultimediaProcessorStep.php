<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor\Step;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ImportMultimediaCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeMultimediaReader;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

/**
 */
final class ErgonodeMultimediaProcessorStep implements ErgonodeProcessorStepInterface
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
        $reader = new ErgonodeMultimediaReader($directory, 'multimedia.csv');

        while ($multimedia = $reader->read()) {
            $command = new ImportMultimediaCommand(
                $import->getId(),
                new MultimediaId($multimedia->getId()),
                $multimedia->getFilename(),
                $multimedia->getExtension(),
                $multimedia->getMime(),
                $multimedia->getSize(),
                new TranslatableString($multimedia->getTranslations())
            );
            $this->commandBus->dispatch($command);
        }
    }
}