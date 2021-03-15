<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateProductCommand;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;

class ProductValueChangedEventHandler implements MessageSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(ProductValueChangedEvent $event): void
    {
        $this->commandBus->dispatch(new CalculateProductCommand($event->getAggregateId()), true);
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield ProductValueChangedEvent::class => ['priority' => -100];
    }
}
