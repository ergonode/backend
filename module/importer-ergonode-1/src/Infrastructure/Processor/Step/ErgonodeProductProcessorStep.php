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
use Ergonode\ImporterErgonode\Infrastructure\Reader\ErgonodeProductReader;
use Ergonode\ImporterErgonode\Infrastructure\Resolver\ProductCommandResolver;

/**
 */
final class ErgonodeProductProcessorStep implements ErgonodeProcessorStepInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var ProductCommandResolver
     */
    private ProductCommandResolver $commandResolver;

    /**
     * @param CommandBusInterface    $commandBus
     * @param ProductCommandResolver $commandResolver
     */
    public function __construct(
        CommandBusInterface $commandBus,
        ProductCommandResolver $commandResolver
    ) {
        $this->commandBus = $commandBus;
        $this->commandResolver = $commandResolver;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeProductReader($directory, 'products.csv');

        while ($product = $reader->read()) {
            $command = $this->commandResolver->resolve($import, $product);
            $this->commandBus->dispatch($command);
        }
    }
}
