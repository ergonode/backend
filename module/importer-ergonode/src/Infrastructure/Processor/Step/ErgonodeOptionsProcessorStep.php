<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor\Step;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeOptionReader;

/**
 */
final class ErgonodeOptionsProcessorStep implements ErgonodeProcessorStepInterface
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
        $reader = new ErgonodeOptionReader($directory, 'options.csv');

        while ($option = $reader->read()) {
            $command = new ImportOptionCommand(
                $import->getId(),
                new AttributeCode($option->getAttribute()),
                new OptionKey($option->getCode()),
                new TranslatableString($option->getTranslations())
            );
            $this->commandBus->dispatch($command);
        }
    }
}