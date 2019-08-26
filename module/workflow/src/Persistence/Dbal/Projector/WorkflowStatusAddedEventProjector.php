<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent;

/**
 */
class WorkflowStatusAddedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'workflow_status';

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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof WorkflowStatusAddedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws ProjectorException
     * @throws UnsupportedEventException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof WorkflowStatusAddedEvent) {
            throw new UnsupportedEventException($event, WorkflowStatusAddedEvent::class);
        }

        $this->connection->beginTransaction();
        try {
            $this->connection->insert(
                self::TABLE,
                [
                    'workflow_id' => $aggregateId->getValue(),
                    'code' => $event->getCode(),
                    'name' => json_encode($event->getStatus()->getName()->getTranslations()),
                    'description' => json_encode($event->getStatus()->getDescription()->getTranslations()),
                    'color' => $event->getStatus()->getColor()->getValue(),
                ]
            );

            $this->connection->commit();
        } catch (\Throwable $exception) {
            throw new ProjectorException($event, $exception);
        }
    }
}
