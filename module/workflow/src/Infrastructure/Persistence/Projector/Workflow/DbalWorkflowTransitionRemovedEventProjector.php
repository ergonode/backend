<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;

class DbalWorkflowTransitionRemovedEventProjector
{
    private const TABLE = 'workflow_transition';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(WorkflowTransitionRemovedEvent $event): void
    {
        $this->connection->delete(
            self::TABLE,
            [
                'workflow_id' => $event->getAggregateId()->getValue(),
                'from_id' => $event->getFrom()->getValue(),
                'to_id' => $event->getTo()->getValue(),
            ]
        );
    }
}
