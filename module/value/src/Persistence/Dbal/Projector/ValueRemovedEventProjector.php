<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Value\Domain\Event\ValueRemovedEvent;
use JMS\Serializer\SerializerInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ValueRemovedEventProjector
{
    private const NAMESPACE = '0cc20207-d1b7-460b-8ef6-6898d00de4c0';
    private const RELATION_TABLE = 'entity_attribute_value';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

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
     * @param ValueRemovedEvent $event
     *
     * @throws \Throwable
     */
    public function __invoke(ValueRemovedEvent $event): void
    {
        $this->connection->transactional(function () use ($event) {
            $attributeId = AttributeId::fromKey($event->getAttributeCode()->getValue());
            $oldValue = $this->serializer->serialize($event->getOld(), 'json');
            $oldValueId = Uuid::uuid5(self::NAMESPACE, $oldValue);

            $this->connection->delete(
                self::RELATION_TABLE,
                [
                    'entity_id' => $event->getAggregateId()->getValue(),
                    'attribute_id' => $attributeId->getValue(),
                    'value_id' => $oldValueId->toString(),
                ]
            );
        });
    }
}
