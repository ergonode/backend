<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Value\Domain\Event\ValueAddedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ValueAddedEventProjector implements DomainEventProjectorInterface
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
    public function supports(DomainEventInterface $event): bool
    {
        return $event instanceof ValueAddedEvent;
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
        if (!$this->supports($event)) {
            throw new UnsupportedEventException($event, ValueAddedEvent::class);
        }

        $this->connection->transactional(function () use ($event, $aggregateId) {
            $attributeId = AttributeId::fromKey($event->getAttributeCode());
            $type = get_class($event->getValue());
            $value = $this->serializer->serialize($event->getValue(), 'json');

            $valueId = Uuid::uuid5(self::NAMESPACE, $value);

            $qb = $this->connection->createQueryBuilder();
            $result = $qb->select('*')
                ->from(self::VALUE_TABLE)
                ->where($qb->expr()->eq('id', ':id'))
                ->setParameter(':id', $valueId->toString())
                ->execute()
                ->fetch();

            if (false === $result) {
                $this->connection->executeQuery(
                    sprintf('INSERT INTO %s (id, type, value) VALUES (?, ?, ?) ON CONFLICT DO NOTHING', self::VALUE_TABLE),
                    [$valueId->toString(), $type, $value]
                );
            }

            $this->connection->insert(
                self::RELATION_TABLE,
                [
                    'entity_id' => $aggregateId->getValue(),
                    'attribute_id' => $attributeId->getValue(),
                    'value_id' => $valueId->toString(),
                ]
            );
        });
    }
}
