<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDefaultStatusSetEvent;

class DbalWorkflowDefaultStatusSetEventProjector
{
    private const TABLE = 'workflow';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws DBALException
     */
    public function __invoke(WorkflowDefaultStatusSetEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'default_status' => $event->getStatusId()->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
