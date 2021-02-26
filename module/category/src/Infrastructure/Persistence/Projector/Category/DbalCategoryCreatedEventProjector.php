<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Infrastructure\Persistence\Projector\Category;

use Doctrine\DBAL\Connection;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ramsey\Uuid\Uuid;
use Ergonode\Core\Application\Serializer\SerializerInterface;

class DbalCategoryCreatedEventProjector
{
    private const TABLE = 'category';
    private const VALUE_TABLE = 'attribute_value';
    private const RELATION_TABLE = 'entity_attribute_value';
    private const NAMESPACE = '0cc20207-d1b7-460b-8ef6-6898d00de4c0';

    private Connection $connection;

    private SerializerInterface $serializer;

    public function __construct(Connection $connection, SerializerInterface $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }


    /**
     * {@inheritDoc}
     *
     * @throws \Throwable
     */
    public function __invoke(CategoryCreatedEvent $event): void
    {
        $this->connection->transactional(function () use ($event): void {
            $this->connection->insert(
                self::TABLE,
                [
                    'id' => $event->getAggregateId()->getValue(),
                    'name' => $this->serializer->serialize($event->getName()->getTranslations()),
                    'code' => $event->getCode()->getValue(),
                    'type' => $event->getType(),
                ]
            );

            foreach ($event->getAttributes() as $code => $value) {
                $attributeId = AttributeId::fromKey((new AttributeCode($code))->getValue());
                $type = get_class($value);
                $value = $this->serializer->serialize($value);

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
                        sprintf(
                            'INSERT INTO %s (id, type, value) VALUES (?, ?, ?) ON CONFLICT DO NOTHING',
                            self::VALUE_TABLE
                        ),
                        [$valueId->toString(), $type, $value]
                    );
                }

                $this->connection->insert(
                    self::RELATION_TABLE,
                    [
                        'entity_id' => $event->getAggregateId()->getValue(),
                        'attribute_id' => $attributeId->getValue(),
                        'value_id' => $valueId->toString(),
                    ]
                );
            }
        });
    }
}
