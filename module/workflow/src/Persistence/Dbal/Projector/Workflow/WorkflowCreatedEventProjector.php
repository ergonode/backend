<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector\Workflow;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Workflow\Domain\Entity\StatusId;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;

/**
 */
class WorkflowCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'workflow';

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
        return $event instanceof WorkflowCreatedEvent;
    }

    /**
     * @param AbstractId                                $aggregateId
     * @param DomainEventInterface|WorkflowCreatedEvent $event
     *
     * @throws UnsupportedEventException
     * @throws DBALException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, WorkflowCreatedEvent::class);
        }

        $status = null;
        if (!empty($event->getStatuses())) {
            $statuses = $event->getStatuses();
            $status = reset($statuses);
        }

        $this->connection->insert(
            self::TABLE,
            [
                'id' => $aggregateId->getValue(),
                'default_status' => $status ? StatusId::fromCode($status)->getValue(): null,
            ]
        );
    }
}
