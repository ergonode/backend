<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Product\Domain\Query\ProductRelationAttributeGridQueryInterface;

class DbalProductRelationAttributeGridQuery implements ProductRelationAttributeGridQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const PRODUCT_VALUE_TABLE = 'public.product_value';

    private Connection $connection;

    protected LanguageQueryInterface $query;

    protected ProductAttributeLanguageResolver $resolver;

    protected DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder;

    protected DefaultImageQueryBuilderInterface $defaultImageQueryBuilder;

    public function __construct(
        Connection $connection,
        LanguageQueryInterface $query,
        ProductAttributeLanguageResolver $resolver,
        DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder,
        DefaultImageQueryBuilderInterface $defaultImageQueryBuilder
    ) {
        $this->connection = $connection;
        $this->query = $query;
        $this->resolver = $resolver;
        $this->defaultLabelQueryBuilder = $defaultLabelQueryBuilder;
        $this->defaultImageQueryBuilder = $defaultImageQueryBuilder;
    }

    public function getGridQuery(ProductId $id, Language $language): QueryBuilder
    {
        $info = $this->query->getLanguageNodeInfo($language);

        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id, p.sku, p.template_id')
            ->addSelect(sprintf('(SELECT EXISTS(%s) as attached)', $this->getSubQuery()->getSQL()))
            ->from(self::PRODUCT_TABLE, 'p')
            ->andWhere($qb->expr()->neq('p.id', ':id'))
            ->groupBy('p.id, p.sku, p.template_id')
            ->having($qb->expr()->gt('count(*)', ':count'))
            ->setParameter(':id', $id->getValue())
            ->setParameter(':count', 0);

        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);

        return $qb;
    }

    private function getSubQuery(): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query->select('DISTINCT pv.product_id')
            ->from('value_translation', 'vt')
            ->join('vt', self::PRODUCT_VALUE_TABLE, 'pv', 'pv.value_id = vt.value_id')
            ->andWhere($query->expr()->like('vt.value', 'concat(\'%\', p.id::TEXT, \'%\')'));
    }
}
