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
use Ergonode\Transformer\Domain\Event\TransformerCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 */
class DbalTransformerProjector implements EventSubscriberInterface
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
            TransformerCreatedEvent::class => 'transformerCreatedEvent',
        ];
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws DBALException
     */
    public function transformerCreatedEvent(DomainEventEnvelope $envelope): void
    {
        $event = $envelope->getEvent();

        if (!$event instanceof TransformerCreatedEvent) {
            throw new \RuntimeException('bad event');
        }

        $this->connection->insert(
            'importer.transformer',
            [
                'id' => $envelope->getAggregateId()->getValue(),
                'name' => $event->getName(),
                'key' => $event->getKey(),
            ]
        );
    }
}
