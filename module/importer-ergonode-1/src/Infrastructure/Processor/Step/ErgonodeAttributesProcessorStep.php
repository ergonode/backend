<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Processor\Step;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode1\Domain\Command\Import\ImportAttributeCommand;
use Ergonode\ImporterErgonode1\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeAttributeReader;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

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
                new AttributeId($attribute->getId()),
                new AttributeCode($attribute->getCode()),
                $attribute->getType(),
                new AttributeScope($attribute->getScope()),
                $attribute->getParameters(),
                $attribute->getName(),
                $attribute->getHint(),
                $attribute->getPlaceholder()
            );
            $this->commandBus->dispatch($command);
        }
    }
}
