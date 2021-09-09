<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Transition;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent;

class TransitionConditionSetChangedEventProjector
{
    private const TABLE = 'workflow_transition';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke(TransitionConditionSetChangedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'condition_set_id' => $event->getConditionSetId()?$event->getConditionSetId()->getValue():null,
            ],
            [
                'transition_id' => $event->getTransitionId()->getValue(),
            ]
        );
    }
}
