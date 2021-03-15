<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;

class DbalWorkflowTransitionAddedEventProjector
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
    public function __invoke(WorkflowTransitionAddedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE,
            [
                'workflow_id' => $event->getAggregateId()->getValue(),
                'source_id' => $event->getTransition()->getFrom()->getValue(),
                'destination_id' => $event->getTransition()->getTo()->getValue(),
            ]
        );
    }
}
