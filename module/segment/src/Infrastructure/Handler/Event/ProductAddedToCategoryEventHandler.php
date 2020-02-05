<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Ergonode\Product\Domain\Event\ProductAddedToCategoryEvent;

/**
 */
class ProductAddedToCategoryEventHandler implements MessageSubscriberInterface
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
     * @param ProductAddedToCategoryEvent $event
     */
    public function __invoke(ProductAddedToCategoryEvent $event)
    {
        $command = new CalculateProductCommand($event->getAggregateId());
        $this->commandBus->dispatch($command);
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield ProductAddedToCategoryEvent::class => ['priority' => -100];
    }
}
