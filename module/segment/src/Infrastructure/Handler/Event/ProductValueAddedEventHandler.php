<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateSegmentProductCommand;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;

/**
 */
class ProductValueAddedEventHandler implements MessageSubscriberInterface
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
    public function __invoke(ProductValueAddedEvent $event)
    {
        $command = new CalculateSegmentProductCommand($event->getAggregateId());
        $this->commandBus->dispatch($command);
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield ProductValueAddedEvent::class => ['priority' => -100];
    }
}
