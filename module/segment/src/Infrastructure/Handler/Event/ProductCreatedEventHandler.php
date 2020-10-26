<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class ProductCreatedEventHandler implements MessageSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(ProductCreatedEvent $event): void
    {
        $command = new CalculateProductCommand($event->getAggregateId());
        $this->commandBus->dispatch($command);
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield ProductCreatedEvent::class => ['priority' => -100];
    }
}
