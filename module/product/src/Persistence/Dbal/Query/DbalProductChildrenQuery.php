<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;

/**
 */
class DbalProductChildrenQuery implements ProductChildrenQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const PRODUCT_CHILDREN_TABLE = 'public.product_children';
    private const PRODUCT_VALUE_TABLE = 'public.product_value';
    private const TEMPLATE_TABLE = 'designer.template';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var LanguageQueryInterface
     */
    protected LanguageQueryInterface $query;

    /**
     * @var DefaultLabelQueryBuilderInterface
     */
    protected DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder;

    /**
     * @var DefaultImageQueryBuilderInterface
     */
    protected DefaultImageQueryBuilderInterface $defaultImageQueryBuilder;

    /**
     * @param Connection                        $connection
     * @param LanguageQueryInterface            $query
     * @param DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder
     * @param DefaultImageQueryBuilderInterface $defaultImageQueryBuilder
     */
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


    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductId $productId, Language $language): DataSetInterface
    {
        $info = $this->query->getLanguageNodeInfo($language);
        $qb = $this->getQuery();
        $qb->andWhere($qb->expr()->eq('product_id', ':product_id'));
        $qb->addSelect('product_id');
        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $result = $this->connection->createQueryBuilder();
        $result->setParameter(':product_id', $productId->getValue());
        $result->select('*');
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }

    /**
     * @param ProductId     $productId
     * @param AttributeId[] $bindings
     * @param Language      $language
     *
     * @return DataSetInterface
     */
    public function getChildrenAndAvailableProductsDataSet(
        ProductId $productId,
        array $bindings,
        Language $language
    ): DataSetInterface {
        $info = $this->query->getLanguageNodeInfo($language);
        $bindingValues = [];
        foreach ($bindings as $binding) {
            $bindingValues[] = $binding->getValue();
        }

        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id, p.sku, dt.name as template')
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', self::PRODUCT_VALUE_TABLE, 'pv', 'p.id = pv.product_id')
            ->join('p', self::TEMPLATE_TABLE, 'dt', 'p.template_id = dt.id')
            ->where($qb->expr()->in('pv.attribute_id', ':bindings'))
            ->andWhere('p.type = \'SIMPLE-PRODUCT\'')
            ->groupBy('p.id, p.sku, dt.name')
            ->having($qb->expr()->gt('count(*)', ':count'));

        $subQb = $this->connection->createQueryBuilder();
        $subQb->select('pc.child_id as attach_flag')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->where($subQb->expr()->eq('pc.product_id', ':id'))
            ->andWhere($subQb->expr()->eq('pc.child_id', 'p.id'));

        $qb->addSelect(sprintf('(%s)', $subQb->getSQL()));

        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->setParameter(':bindings', $bindingValues, Connection::PARAM_INT_ARRAY);
        $result->setParameter(':count', (count($bindingValues) - 1));
        $result->setParameter(':id', $productId->getValue());
        $result->from(sprintf('(%s)', $qb->getSQL()), 't');

        return new DbalDataSet($result);
    }


    /**
     * {@inheritDoc}
     */
    public function findProductIdByProductChildrenId(ProductId $id): array
    {
        $qb = $this->connection->createQueryBuilder()
            ->select('pc.product_id')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc');

        $result = $qb
            ->where($qb->expr()->eq('child_id', ':id'))
            ->setParameter(':id', $id->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);

        if (false === $result) {
            $result = [];
        }

        foreach ($result as &$item) {
            $item = new ProductId($item);
        }

        return $result;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.sku')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->innerJoin('pc', self::PRODUCT_TABLE, 'p', 'p.id = pc.child_id');
    }
}
