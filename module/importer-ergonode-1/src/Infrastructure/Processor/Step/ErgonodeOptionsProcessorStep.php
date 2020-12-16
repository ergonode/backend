<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Command\Import\ImportOptionCommand;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeOptionReader;

class ErgonodeOptionsProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'options.csv';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeOptionReader($directory, self::FILENAME);

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
