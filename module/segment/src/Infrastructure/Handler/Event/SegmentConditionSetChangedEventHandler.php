<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateSegmentCommand;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class SegmentConditionSetChangedEventHandler implements MessageSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(SegmentConditionSetChangedEvent $event)
    {
        if ($event->getTo()) {
            $command = new CalculateSegmentCommand($event->getAggregateId());
            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @return iterable
     */
    public static function getHandledMessages(): iterable
    {
        yield SegmentConditionSetChangedEvent::class => ['priority' => -100];
    }
}
