<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Product\Domain\Event\ProductValueAddedEvent;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductValueAddedEventProjector
{
    private const TABLE_PRODUCT_VALUE = 'product_value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductValueAddedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductValueAddedEvent $event): void
    {
        $productId = $event->getAggregateId()->getValue();

        $attributeId = AttributeId::fromKey($event->getAttributeCode()->getValue())->getValue();
        $this->insertValue($productId, $attributeId, $event->getValue());
    }

    /**
     * @param string         $productId
     * @param string         $attributeId
     * @param ValueInterface $value
     *
     * @throws DBALException
     */
    private function insertValue(string $productId, string $attributeId, ValueInterface $value): void
    {
        if ($value instanceof StringValue) {
            $this->insert($productId, $attributeId, $value->getValue());
        } elseif ($value instanceof StringCollectionValue) {
            foreach ($value->getValue() as $phrase) {
                $this->insert($productId, $attributeId, $phrase);
            }
        } elseif ($value instanceof TranslatableStringValue) {
            $translation = $value->getValue();
            foreach ($translation as $language => $phrase) {
                $this->insert($productId, $attributeId, $phrase, $language);
            }
        } else {
            throw new \RuntimeException(sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue()))));
        }
    }

    /**
     * @param string      $productId
     * @param string      $attributeId
     * @param string      $value
     * @param string|null $language
     *
     * @throws DBALException
     */
    private function insert(string $productId, string $attributeId, string $value, string $language = null): void
    {
        if ('' !== $value) {
            $valueId = Uuid::uuid5(ValueInterface::NAMESPACE, implode('|', [$value, $language]));

            $qb = $this->connection->createQueryBuilder();
            $result = $qb->select('*')
                ->from(self::TABLE_VALUE_TRANSLATION)
                ->where($qb->expr()->eq('id', ':id'))
                ->setParameter(':id', $valueId->toString())
                ->execute()
                ->fetch();

            if (false === $result) {
                $this->connection->executeQuery(
                    'INSERT INTO value_translation (id, value_id, value, language) '.
                    ' VALUES (?, ?, ?, ?) ON CONFLICT DO NOTHING',
                    [$valueId->toString(), $valueId->toString(), $value, $language ?: null]
                );
            }

            $this->connection->insert(
                self::TABLE_PRODUCT_VALUE,
                [
                    'product_id' => $productId,
                    'attribute_id' => $attributeId,
                    'value_id' => $valueId,
                ]
            );
        }
    }
}
