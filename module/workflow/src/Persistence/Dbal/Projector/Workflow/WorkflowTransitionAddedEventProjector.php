<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class WorkflowTransitionAddedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'workflow_transition';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param Connection          $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof WorkflowTransitionAddedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof WorkflowTransitionAddedEvent) {
            throw new UnsupportedEventException($event, WorkflowTransitionAddedEvent::class);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'workflow_id' => $aggregateId->getValue(),
                'source_id' => StatusId::fromCode($event->getTransition()->getFrom())->getValue(),
                'destination_id' => StatusId::fromCode($event->getTransition()->getTo())->getValue(),
            ]
        );
    }
}
