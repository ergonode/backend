<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;

class DbalWorkflowStatusAddedEventProjector
{
    private const TABLE = 'workflow';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(WorkflowStatusAddedEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'default_status' => $event->getStatusId()->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
                'default_status' => null,
            ]
        );
    }
}
