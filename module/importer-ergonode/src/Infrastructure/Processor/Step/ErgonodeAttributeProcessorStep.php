<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Processor\Step;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Domain\Entity\Import;
use Ergonode\ImporterErgonode\Infrastructure\Processor\ErgonodeProcessorStepInterface;
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeAttributeReader;
use Ergonode\ImporterErgonode\Infrastructure\Resolver\AttributeCommandResolver;

/**
 */
final class ErgonodeAttributeProcessorStep implements ErgonodeProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var AttributeCommandResolver
     */
    private AttributeCommandResolver $commandResolver;

    /**
     * @param CommandBusInterface      $commandBus
     * @param AttributeCommandResolver $commandResolver
     */
    public function __construct(
        CommandBusInterface $commandBus,
        AttributeCommandResolver $commandResolver
    ) {
        $this->commandBus = $commandBus;
        $this->commandResolver = $commandResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeAttributeReader($directory, 'attributes.csv');

        while ($attribute = $reader->read()) {
            $command = $this->commandResolver->resolve($import, $attribute);
            $this->commandBus->dispatch($command);
        }
    }
}
