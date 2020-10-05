<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event;

use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Completeness\Domain\Command\ProductCompletenessCalculateCommand;

/**
 */
class ProductValueAddedEventHandler
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
     * @param ProductValueAddedEvent $event
     */
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $command = new ProductCompletenessCalculateCommand($event->getAggregateId());
        $this->commandBus->dispatch($command, true);
    }
}
