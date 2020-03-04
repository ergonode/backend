<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDefaultStatusSetEvent;

/**
 */
class WorkflowDefaultStatusSetEventProjector
{
    private const TABLE = 'workflow';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param WorkflowDefaultStatusSetEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(WorkflowDefaultStatusSetEvent $event): void
    {
        $this->connection->update(
            self::TABLE,
            [
                'default_status' => StatusId::fromCode($event->getCode()->getValue())->getValue(),
            ],
            [
                'id' => $event->getAggregateId()->getValue(),
            ]
        );
    }
}
