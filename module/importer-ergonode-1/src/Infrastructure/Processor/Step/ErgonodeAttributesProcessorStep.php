<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportAttributeCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeAttributeReader;

class ErgonodeAttributesProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'attributes.csv';

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeAttributeReader($directory, self::FILENAME);

        while ($attribute = $reader->read()) {
            $command = new ImportAttributeCommand(
                $import->getId(),
                $attribute->getCode(),
                $attribute->getType(),
                $attribute->getScope(),
                $attribute->getParameters(),
                $attribute->getName(),
                $attribute->getHint(),
                $attribute->getPlaceholder()
            );
            $import->addRecords(1);
            $this->commandBus->dispatch($command, true);
        }
    }
}
