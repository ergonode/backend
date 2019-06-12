<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\Transformer\Domain\Event\ProcessorCreatedEvent;
use Ergonode\Transformer\Domain\Event\ProcessorStatusChangedEvent;
use Ergonode\Transformer\Domain\ValueObject\ProcessorStatus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class DbalProcessorProjector implements EventSubscriberInterface
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
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ProcessorCreatedEvent::class => 'processorCreatedEvent',
            ProcessorStatusChangedEvent::class => 'processorStatusChangedEvent',
        ];
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws DBALException
     */
    public function processorCreatedEvent(DomainEventEnvelope $envelope): void
    {
        $event = $envelope->getEvent();

        if (!$event instanceof ProcessorCreatedEvent) {
            throw new \RuntimeException('bad event');
        }

        $this->connection->insert(
            'importer.processor',
            [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'id' => $envelope->getAggregateId()->getValue(),
                'import_id' => $event->getImportId()->getValue(),
                'transformer_id' => $event->getTransformerId()->getValue(),
                'action' => $event->getAction(),
                'status' => ProcessorStatus::CREATED,
            ]
        );
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws DBALException
     */
    public function processorStatusChangedEvent(DomainEventEnvelope $envelope): void
    {
        $event = $envelope->getEvent();

        if (!$event instanceof ProcessorStatusChangedEvent) {
            throw new \RuntimeException('bad event');
        }

        if ($event->getTo()->isProcessed()) {
            $this->connection->update(
                'importer.processor',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'started_at' => date('Y-m-d H:i:s'),
                    'status' => ProcessorStatus::PRECESSED,
                ],
                [
                    'id' => $envelope->getAggregateId()->getValue(),
                ]
            );
        }

        if ($event->getTo()->isEnded()) {
            $this->connection->update(
                'importer.processor',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'ended_at' => date('Y-m-d H:i:s'),
                    'status' => ProcessorStatus::ENDED,
                ],
                [
                    'id' => $envelope->getAggregateId()->getValue(),
                ]
            );
        }

        if ($event->getTo()->isStopped()) {
            $this->connection->update(
                'importer.processor',
                [
                    'updated_at' => date('Y-m-d H:i:s'),
                    'ended_at' => date('Y-m-d H:i:s'),
                    'status' => ProcessorStatus::STOPPED,
                ],
                [
                    'id' => $envelope->getAggregateId()->getValue(),
                ]
            );
        }
    }
}
