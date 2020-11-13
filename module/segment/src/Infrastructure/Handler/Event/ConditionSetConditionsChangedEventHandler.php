<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Event;

use Ergonode\Condition\Domain\Event\ConditionSetConditionsChangedEvent;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Segment\Domain\Command\CalculateSegmentCommand;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;

class ConditionSetConditionsChangedEventHandler
{
    private CommandBusInterface $commandBus;

    private SegmentQueryInterface $query;

    public function __construct(CommandBusInterface $commandBus, SegmentQueryInterface $query)
    {
        $this->commandBus = $commandBus;
        $this->query = $query;
    }

    public function __invoke(ConditionSetConditionsChangedEvent $event): void
    {
        if ($event->getTo()) {
            $segmentIds = $this->query->findIdByConditionSetId($event->getAggregateId());
            foreach ($segmentIds as $segmentId) {
                $this->commandBus->dispatch(new CalculateSegmentCommand($segmentId), true);
            }
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
