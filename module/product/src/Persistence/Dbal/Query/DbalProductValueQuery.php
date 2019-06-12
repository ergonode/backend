<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Entity\ValueId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Query\ProductValueQueryInterface;

/**
 * Class DbalProductValueQuery
 */
class DbalProductValueQuery implements ProductValueQueryInterface
{
    private const PRODUCT_VALUE_TABLE = 'product_value';
    private const ATTRIBUTE_VALUE_TABLE = 'value';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductId   $productId
     * @param AttributeId $attributeId
     * @param ValueId     $valueId
     *
     * @return null|array
     */
    public function findProductValue(ProductId $productId, AttributeId $attributeId, ValueId $valueId): ?array
    {
        $query = $this->getQuery();

        $result = $query
            ->andWhere($query->expr()->eq('product_id', ':productId'))
            ->andWhere($query->expr()->eq('attribute_id', ':attributeId'))
            ->andWhere($query->expr()->eq('value_id', ':valueId'))
            ->setParameter('productId', $productId->getValue())
            ->setParameter('attributeId', $attributeId->getValue())
            ->setParameter('valueId', $valueId->getValue())
            ->execute()
            ->fetch();

        if ($result) {
            return $result;
        }

        return null;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('pv.value_id')
            ->from(self::ATTRIBUTE_VALUE_TABLE, 'av')
            ->join('av', self::PRODUCT_VALUE_TABLE, 'pv', 'pv.value_id = av.id');
    }
}
