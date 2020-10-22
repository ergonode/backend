<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateSegmentCommand;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class SegmentCreatedEventHandler implements MessageSubscriberInterface
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
     * @param SegmentCreatedEvent $event
     */
    public function __invoke(SegmentCreatedEvent $event)
    {
        if ($event->getConditionSetId()) {
            $command = new CalculateSegmentCommand($event->getAggregateId());
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield SegmentCreatedEvent::class => ['priority' => -100];
    }
}
