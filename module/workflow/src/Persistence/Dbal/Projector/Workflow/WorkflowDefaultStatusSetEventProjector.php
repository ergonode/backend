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
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowDefaultStatusSetEvent;

/**
 */
class WorkflowDefaultStatusSetEventProjector implements DomainEventProjectorInterface
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
        return $event instanceof WorkflowDefaultStatusSetEvent;
    }

    /**
     * @param AbstractId                                         $aggregateId
     * @param DomainEventInterface|WorkflowDefaultStatusSetEvent $event
     *
     * @throws UnsupportedEventException
     * @throws DBALException
     * @throws \Exception
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, WorkflowDefaultStatusSetEvent::class);
        }

        $this->connection->update(
            self::TABLE,
            [
                'default_status' => StatusId::fromCode($event->getCode())->getValue(),
            ],
            [
                'id' => $aggregateId->getValue(),
            ]
        );
    }
}
