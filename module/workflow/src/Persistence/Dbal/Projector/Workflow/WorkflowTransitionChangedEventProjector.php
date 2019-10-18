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
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionChangedEvent;
use JMS\Serializer\SerializerInterface;

/**
 */
class WorkflowTransitionChangedEventProjector implements DomainEventProjectorInterface
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
        return $event instanceof WorkflowTransitionChangedEvent;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof WorkflowTransitionChangedEvent) {
            throw new UnsupportedEventException($event, WorkflowTransitionChangedEvent::class);
        }

        $this->connection->update(
            self::TABLE,
            [
                'name' => $this->serializer->serialize($event->getTo()->getName()->getTranslations(), 'json'),
                'description' => $this->serializer->serialize($event->getTo()->getDescription()->getTranslations(), 'json'),
            ],
            [
                'workflow_id' => $aggregateId->getValue(),
                'source_id' => StatusId::fromCode($event->getTo()->getSource())->getValue(),
                'destination_id' => StatusId::fromCode($event->getTo()->getDestination())->getValue(),
            ]
        );
    }
}
