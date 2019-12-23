<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;

/**
 */
class WorkflowTransitionRemovedEventProjector
{
    private const TABLE = 'workflow_transition';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
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
                'source_id' => StatusId::fromCode($event->getSource())->getValue(),
                'destination_id' => StatusId::fromCode($event->getDestination())->getValue(),
            ]
        );
    }
}
