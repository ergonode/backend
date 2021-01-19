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
use Ergonode\ImporterErgonode1\Infrastructure\Reader\ErgonodeProductReader;
use Ergonode\ImporterErgonode1\Infrastructure\Resolver\ProductCommandResolver;

class ErgonodeProductProcessorStep implements ErgonodeProcessorStepInterface
{
    private const FILENAME = 'products.csv';

    private CommandBusInterface $commandBus;
    private ProductCommandResolver $commandResolver;

    public function __construct(
        CommandBusInterface $commandBus,
        ProductCommandResolver $commandResolver
    ) {
        $this->commandBus = $commandBus;
        $this->commandResolver = $commandResolver;
    }

    public function __invoke(Import $import, string $directory): void
    {
        $reader = new ErgonodeProductReader($directory, self::FILENAME);

        while ($product = $reader->read()) {
            $command = $this->commandResolver->resolve($import, $product);
            $this->commandBus->dispatch($command);
        }
    }
}
