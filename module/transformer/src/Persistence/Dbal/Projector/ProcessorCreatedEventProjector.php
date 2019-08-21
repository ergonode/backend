<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Transformer\Domain\Event\ProcessorCreatedEvent;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;

/**
 */
class ProcessorCreatedEventProjector implements DomainEventProjectorInterface
{
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
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ProcessorCreatedEvent;
    }

    /**
     * {@inheritDoc}
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ProcessorCreatedEvent) {
            throw new UnsupportedEventException($event, ProcessorCreatedEvent::class);
        }

        $this->connection->transactional(function () use ($aggregateId, $event) {
            $date = date('Y-m-d H:i:s');

            $this->connection->insert(
                'importer.processor',
                [
                    'created_at' => $date,
                    'updated_at' => $date,
                    'id' => $aggregateId->getValue(),
                    'import_id' => $event->getImportId()->getValue(),
                    'transformer_id' => $event->getTransformerId()->getValue(),
                    'action' => $event->getAction(),
                    'status' => ProcessorStatus::CREATED,
                ]
            );
        });
    }
}
