<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\EventSourcing\Infrastructure\Exception\ProjectorException;
use Ergonode\EventSourcing\Infrastructure\Exception\UnsupportedEventException;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\EventSourcing\Infrastructure\Projector\DomainEventProjectorInterface;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class CategoryCreatedEventProjector implements DomainEventProjectorInterface
{
    private const TABLE = 'category';
    private const VALUE_TABLE = 'attribute_value';
    private const RELATION_TABLE = 'entity_attribute_value';
    private const NAMESPACE = '0cc20207-d1b7-460b-8ef6-6898d00de4c0';

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
        return $event instanceof CategoryCreatedEvent;
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
        if (!$event instanceof CategoryCreatedEvent) {
            throw new UnsupportedEventException($event, CategoryCreatedEvent::class);
        }

        try {
            $this->connection->beginTransaction();
            $this->connection->insert(
                self::TABLE,
                [
                    'id' => $aggregateId->getValue(),
                    'name' => json_encode($event->getName()->getTranslations()),
                    'code' => $event->getCode()->getValue(),
                ]
            );

            foreach ($event->getAttributes() as $code => $value) {
                $attributeId = AttributeId::fromKey(new AttributeCode($code));
                $type = get_class($value);
                $value = $this->serializer->serialize($value, 'json');

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
            }
            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();
            throw new ProjectorException($event, $exception);
        }
    }
}
