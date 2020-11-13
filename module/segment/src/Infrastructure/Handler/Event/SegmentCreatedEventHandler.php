<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateSegmentCommand;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

class SegmentCreatedEventHandler implements MessageSubscriberInterface
{
    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function __invoke(SegmentCreatedEvent $event): void
    {
        if ($event->getConditionSetId()) {
            $this->commandBus->dispatch(new CalculateSegmentCommand($event->getAggregateId()), true);
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
