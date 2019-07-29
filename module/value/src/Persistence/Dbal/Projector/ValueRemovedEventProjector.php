<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Value\Domain\Event\ValueRemovedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ValueRemovedEventProjector implements DomainEventProjectorInterface
{
    private const NAMESPACE = '0cc20207-d1b7-460b-8ef6-6898d00de4c0';
    private const RELATION_TABLE = 'entity_attribute_value';

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
     * @param DomainEventInterface $event
     *
     * @return bool
     */
    public function support(DomainEventInterface $event): bool
    {
        return $event instanceof ValueRemovedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ValueRemovedEvent) {
            throw new UnsupportedEventException($event, ValueRemovedEvent::class);
        }

        try {
            $attributeId = AttributeId::fromKey($event->getAttributeCode());
            $oldValue = $this->serializer->serialize($event->getOld(), 'json');
            $oldValueId = Uuid::uuid5(self::NAMESPACE, $oldValue);

            $this->connection->delete(
                self::RELATION_TABLE,
                [
                    'entity_id' => $aggregateId->getValue(),
                    'attribute_id' => $attributeId->getValue(),
                    'value_id' => $oldValueId->toString(),
                ]
            );

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }
}
