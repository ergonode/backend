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
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeAttributeReader;

/**
 */
final class ErgonodeAttributeProcessorStep implements ErgonodeProcessorStepInterface
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
        $reader = new ErgonodeAttributeReader($directory, 'attributes.csv');

        while ($attribute = $reader->read()) {
            // @todo Create attribute import
            $command = new ImportAttributeCommand(
                $import->getId(),
                new AttributeCode($attribute->getAttribute()),
                new OptionKey($attribute->getCode()),
                new TranslatableString($attribute->getTranslations())
            );
            $this->commandBus->dispatch($command);
        }
    }
}