<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Product\Domain\Query\AttributeValueQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class DbalAttributeValueQuery implements AttributeValueQueryInterface
{
    private const TABLE_PRODUCT_VALUE = 'product_value';
    private const TABLE_VALUE_TRANSLATION = 'value_translation';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getUniqueValue(AttributeId $attributeId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('DISTINCT(vt.value)')
            ->from(self::TABLE_PRODUCT_VALUE, 'pv')
            ->join('pv', self::TABLE_VALUE_TRANSLATION, 'vt', 'vt.value_id = pv.value_id')
            ->Where($qb->expr()->eq('pv.attribute_id', ':id'))
            ->setParameter(':id', $attributeId->getValue())
        ;

        return $qb->execute()->fetchAll(\PDO::FETCH_COLUMN);
    }
}
