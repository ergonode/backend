<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Value\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\Value\Domain\Event\ValueRemovedEvent;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;

class DbalValueRemovedEventProjector
{
    private const NAMESPACE = '0cc20207-d1b7-460b-8ef6-6898d00de4c0';
    private const RELATION_TABLE = 'entity_attribute_value';

    private Connection $connection;

    private SerializerInterface $serializer;

    private AttributeQueryInterface $attributeQuery;

    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        AttributeQueryInterface $attributeQuery
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->attributeQuery = $attributeQuery;
    }

    /**
     * @throws \Throwable
     */
    public function __invoke(ValueRemovedEvent $event): void
    {
        $this->connection->transactional(function () use ($event): void {
            $attributeId = $this->attributeQuery->findAttributeIdByCode($event->getAttributeCode());
            Assert::notNull($attributeId);
            $oldValue = $this->serializer->serialize($event->getOld());
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
