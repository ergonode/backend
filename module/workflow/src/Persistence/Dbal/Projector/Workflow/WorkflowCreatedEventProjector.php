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
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;

/**
 */
class WorkflowCreatedEventProjector
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
     * @param WorkflowCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(WorkflowCreatedEvent $event): void
    {
        $status = null;
        if (!empty($event->getStatuses())) {
            $statuses = $event->getStatuses();
            $status = reset($statuses);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $event->getAggregateId()->getValue(),
                'code' => $event->getCode(),
                'default_status' => $status ? StatusId::fromCode($status->getValue())->getValue(): null,
            ]
        );
    }
}
