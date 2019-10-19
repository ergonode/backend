<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;

/**
 */
class WorkflowTransitionRemovedEventProjector implements DomainEventProjectorInterface
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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof WorkflowTransitionRemovedEvent;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof WorkflowTransitionRemovedEvent) {
            throw new UnsupportedEventException($event, WorkflowTransitionRemovedEvent::class);
        }

        $this->connection->delete(
            self::TABLE,
            [
                'workflow_id' => $aggregateId->getValue(),
                'source_id' => StatusId::fromCode($event->getSource())->getValue(),
                'destination_id' => StatusId::fromCode($event->getDestination())->getValue(),
            ]
        );
    }
}
