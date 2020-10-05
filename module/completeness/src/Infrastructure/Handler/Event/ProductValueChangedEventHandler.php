<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Completeness\Domain\Command\ProductCompletenessCalculateCommand;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;

/**
 */
class ProductValueChangedEventHandler
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
     * @param ProductValueChangedEvent $event
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $command = new ProductCompletenessCalculateCommand($event->getAggregateId());
        $this->commandBus->dispatch($command, true);
    }
}
