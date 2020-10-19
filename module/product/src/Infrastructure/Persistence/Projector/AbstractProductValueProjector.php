<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Persistence\Projector;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ramsey\Uuid\Uuid;

/**
 */
abstract class AbstractProductValueProjector
{
    private const TABLE_PRODUCT_VALUE = 'product_value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    /**
     * @var Connection
     */
    protected Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param string         $productId
     * @param string         $attributeId
     * @param ValueInterface $value
     *
     * @throws DBALException
     */
    protected function insertValue(string $productId, string $attributeId, ValueInterface $value): void
    {
        foreach ($value->getValue() as $language => $phrase) {
            $this->insert($productId, $attributeId, $phrase, $language);
        }
    }

    /**
     * @param string      $productId
     * @param string      $attributeId
     * @param string|null $value
     * @param string|null $language
     *
     * @throws DBALException
     */
    protected function insert(string $productId, string $attributeId, ?string $value, ?string $language = null): void
    {
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

    /**
     * @param string $productId
     * @param string $attributeId
     *
     * @throws DBALException
     */
    protected function delete(string $productId, string $attributeId): void
    {
        $this->connection->delete(
            self::TABLE_PRODUCT_VALUE,
            [
                'product_id' => $productId,
                'attribute_id' => $attributeId,
            ]
        );
    }
}
