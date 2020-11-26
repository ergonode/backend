<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Product\Domain\Query\ProductCategoryQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

class DbalProductCategoryQuery implements ProductCategoryQueryInterface
{
    private const PRODUCT_CATEGORY_TABLE = 'public.product_category';

    private Connection $connection;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(Connection $connection, DbalDataSetFactory $dataSetFactory)
    {
        $this->connection = $connection;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSetByProduct(Language $language, ProductId $productId): DataSetInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('c.id, c.code, pc.product_id')
            ->from(self::PRODUCT_CATEGORY_TABLE, 'pc')
            ->leftJoin(
                'pc',
                'category',
                'c',
                'pc.category_id = c.id'
            );
        $qb->addSelect(sprintf('(c.name->>\'%s\') AS name', $language->getCode()));

        $qb->where($qb->expr()->eq('product_id', ':productId'));

        $result = $this->connection->createQueryBuilder();

        $result->setParameter(':productId', $productId->getValue());
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }
}
