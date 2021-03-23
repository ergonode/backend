<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Ergonode\Product\Application\Event\ProductUpdatedEvent;

class ProductUpdatedEventHandler
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(ProductUpdatedEvent $event): void
    {
        $this->commandBus->dispatch(new CalculateProductCommand($event->getProduct()->getId()), true);
    }
}
