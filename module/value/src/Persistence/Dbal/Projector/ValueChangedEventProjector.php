<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Value\Domain\Event\ValueChangedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ValueChangedEventProjector implements DomainEventProjectorInterface
{
    private const NAMESPACE = '0cc20207-d1b7-460b-8ef6-6898d00de4c0';
    private const VALUE_TABLE = 'attribute_value';
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
        return $event instanceof ValueChangedEvent;
    }

    /**
     * @param AbstractId           $aggregateId
     * @param DomainEventInterface $event
     *
     * @throws UnsupportedEventException
     * @throws \Throwable
     */
    public function projection(AbstractId $aggregateId, DomainEventInterface $event): void
    {
        if (!$event instanceof ValueChangedEvent) {
            throw new UnsupportedEventException($event, ValueChangedEvent::class);
        }

        $this->connection->transactional(function () use ($event, $aggregateId) {
            $attributeId = AttributeId::fromKey($event->getAttributeCode());
            $type = get_class($event->getTo());
            $newValue = $this->serializer->serialize($event->getTo(), 'json');
            $oldValue = $this->serializer->serialize($event->getTo(), 'json');

            $newValueId = Uuid::uuid5(self::NAMESPACE, $newValue);
            $oldValueId = Uuid::uuid5(self::NAMESPACE, $oldValue);

            $qb = $this->connection->createQueryBuilder();
            $result = $qb->select('*')
                ->from(self::VALUE_TABLE)
                ->where($qb->expr()->eq('id', ':id'))
                ->setParameter(':id', $newValueId->toString())
                ->execute()
                ->fetch();

            if (false === $result) {
                $this->connection->executeQuery(
                    sprintf('INSERT INTO %s (id, type, value) VALUES (?, ?, ?) ON CONFLICT DO NOTHING', self::VALUE_TABLE),
                    [$newValueId->toString(), $type, $newValueId]
                );
            }

            $this->connection->insert(
                self::RELATION_TABLE,
                [
                    'entity_id' => $aggregateId->getValue(),
                    'attribute_id' => $attributeId->getValue(),
                    'value_id' => $newValueId->toString(),
                ]
            );

            $this->connection->delete(
                self::RELATION_TABLE,
                [
                    'entity_id' => $aggregateId->getValue(),
                    'attribute_id' => $attributeId->getValue(),
                    'value_id' => $oldValueId->toString(),
                ]
            );
        });
    }
}
