<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
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

    public function __invoke(SegmentConditionSetChangedEvent $event): void
    {
        if ($event->getTo()) {
            $this->commandBus->dispatch(new CalculateSegmentCommand($event->getAggregateId()), true);
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
