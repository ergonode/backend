<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductChildrenGridQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class DbalProductChildrenGridQuery implements ProductChildrenGridQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const PRODUCT_CHILDREN_TABLE = 'public.product_children';

    private Connection $connection;

    protected LanguageQueryInterface $query;

    protected DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder;

    protected DefaultImageQueryBuilderInterface $defaultImageQueryBuilder;

    public function __construct(
        Connection $connection,
        LanguageQueryInterface $query,
        DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder,
        DefaultImageQueryBuilderInterface $defaultImageQueryBuilder
    ) {
        $this->connection = $connection;
        $this->query = $query;
        $this->defaultLabelQueryBuilder = $defaultLabelQueryBuilder;
        $this->defaultImageQueryBuilder = $defaultImageQueryBuilder;
    }

    public function getGridQuery(ProductId $productId, Language $language): QueryBuilder
    {
        $info = $this->query->getLanguageNodeInfo($language);

        $qb = $this->connection->createQueryBuilder();

        $qb->select('p.id, p.sku')
            ->addSelect('product_id')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->innerJoin('pc', self::PRODUCT_TABLE, 'p', 'p.id = pc.child_id')
            ->andWhere($qb->expr()->eq('product_id', ':product_id'))
                ->setParameter(':product_id', $productId->getValue());

        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);

        return $qb;
    }
}
