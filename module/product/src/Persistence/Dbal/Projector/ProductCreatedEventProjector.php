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
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Product\Domain\Event\ProductCreatedEvent;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
class ProductCreatedEventProjector
{
    private const NAMESPACE = 'cb2600df-94fb-4755-9e6a-a15591a8e510';
    private const TABLE_PRODUCT = 'product';
    private const TABLE_PRODUCT_CATEGORY = 'product_category_product';
    private const TABLE_PRODUCT_VALUE = 'product_value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var array
     */
    private array $cache = [];

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductCreatedEvent $event
     *
     * @throws DBALException
     */
    public function __invoke(ProductCreatedEvent $event): void
    {
        $this->connection->insert(
            self::TABLE_PRODUCT,
            [
                'id' => $event->getAggregateId()->getValue(),
                'sku' => $event->getSku()->getValue(),
                'status' => 'new',
            ]
        );

        foreach ($event->getCategories() as $categoryId) {
            $this->connection->insert(
                self::TABLE_PRODUCT_CATEGORY,
                [
                    'product_id' => $event->getAggregateId()->getValue(),
                    'category_id' => $categoryId->getValue(),
                ]
            );
        }

        foreach ($event->getAttributes() as $code => $value) {
            $attributeId = AttributeId::fromKey((new AttributeCode($code))->getValue())->getValue();
            $this->insertValue($event->getAggregateId()->getValue(), $attributeId, $value);
        }
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
            throw new \RuntimeException(
                sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue())))
            );
        }
    }

    /**
     * @param string      $productId
     * @param string      $attributeId
     * @param string      $value
     * @param string|null $language
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function insert(string $productId, string $attributeId, string $value, string $language = null): void
    {
        if ('' !== $value) {
            $valueId = Uuid::uuid5(self::NAMESPACE, implode('|', [$value, $language]))->toString();

            if (!array_key_exists($valueId, $this->cache)) {
                $result = $this->provideValue($valueId, $value, $language);
                $this->cache[$valueId] = $result;
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

    /**
     * @param string      $valueId
     * @param string      $value
     * @param string|null $language
     *
     * @return bool
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function provideValue(string $valueId, string $value, string $language = null): bool
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('*')
            ->from(self::TABLE_VALUE_TRANSLATION)
            ->where($qb->expr()->eq('id', ':id'))
            ->setParameter(':id', $valueId)
            ->execute()
            ->fetch();

        if (!$result) {
            $this->connection->executeQuery(
                'INSERT INTO value_translation (id, value_id, value, language) '.
                ' VALUES (?, ?, ?, ?) ON CONFLICT DO NOTHING',
                [$valueId, $valueId, $value, $language ?: null]
            );
        }

        return true;
    }
}
