<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Projector;

use Doctrine\Common\Collections\Expr\Value;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Doctrine\DBAL\DBALException;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ramsey\Uuid\Uuid;
use Doctrine\DBAL\Connection;

/**
 */
abstract class AbstractProductValueProjector
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
     * @param string         $productId
     * @param string         $attributeId
     * @param ValueInterface $value
     *
     * @throws DBALException
     */
    protected function insertValue(string $productId, string $attributeId, ValueInterface $value): void
    {
        $class = get_class($value);
        switch ($class) {
            case StringValue::class:
                $this->insert($productId, $attributeId, (string) $value);
                break;
            case StringCollectionValue::class:
                foreach ($value->getValue() as $language => $phrase) {
                    $this->insert($productId, $attributeId, $phrase, $language);
                }
                break;
            case TranslatableStringValue::class:
                $translation = $value->getValue();
                foreach ($translation as $language => $phrase) {
                    $this->insert($productId, $attributeId, $phrase, $language);
                }
                break;
            default:
                throw new \RuntimeException(
                    sprintf(sprintf('Unknown Value class "%s"', \get_class($value->getValue())))
                );
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
}
