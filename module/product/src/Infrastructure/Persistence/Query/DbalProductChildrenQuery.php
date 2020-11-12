<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;

class DbalProductChildrenQuery implements ProductChildrenQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';
    private const PRODUCT_CHILDREN_TABLE = 'public.product_children';
    private const PRODUCT_VALUE_TABLE = 'public.product_value';
    private const VALUE_TRANSLATION_TABLE = 'public.value_translation';
    private const LANGUAGE_TREE_TABLE = 'public.language_tree';

    private Connection $connection;

    protected LanguageQueryInterface $query;

    protected ProductAttributeLanguageResolver $resolver;

    protected DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder;

    protected DefaultImageQueryBuilderInterface $defaultImageQueryBuilder;

    public function __construct(
        Connection $connection,
        LanguageQueryInterface $query,
        DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder,
        DefaultImageQueryBuilderInterface $defaultImageQueryBuilder,
        ProductAttributeLanguageResolver $resolver
    ) {
        $this->connection = $connection;
        $this->query = $query;
        $this->defaultLabelQueryBuilder = $defaultLabelQueryBuilder;
        $this->defaultImageQueryBuilder = $defaultImageQueryBuilder;
        $this->resolver = $resolver;
    }


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
     * @param AbstractAttribute[] $bindingAttributes
     */
    public function getChildrenAndAvailableProductsDataSet(
        AbstractAssociatedProduct $product,
        Language $language,
        array $bindingAttributes
    ): DataSetInterface {
        $info = $this->query->getLanguageNodeInfo($language);
        $count = 0;
        $bindingValues = [];

        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id, p.sku, p.template_id')
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', self::PRODUCT_VALUE_TABLE, 'pv', 'p.id = pv.product_id')
            ->where('p.type = :type')
            ->groupBy('p.id, p.sku, p.template_id')
            ->having($qb->expr()->gt('count(*)', ':count'));

        if ($product instanceof VariableProduct) {
            foreach ($bindingAttributes as $bindingAttribute) {
                $bindingValues[] = $bindingAttribute->getId()->getValue();
                $this->addBinding($qb, $bindingAttribute, $language);
            }
            $count = (count($bindingValues) - 1);
            $qb->andWhere($qb->expr()->in('pv.attribute_id', ':bindings'));
        }
        $subQbAttached = $this->connection->createQueryBuilder();
        $subQbAttached->select('pc.child_id')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->where($subQbAttached->expr()->eq('pc.product_id', ':id'))
            ->andWhere($subQbAttached->expr()->eq('pc.child_id', 'p.id'));

        $qb->addSelect(sprintf('(SELECT EXISTS(%s) as attached)', $subQbAttached->getSQL()));

        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->setParameter(':count', $count);
        $result->setParameter(':id', $product->getId()->getValue());
        $result->setParameter(':type', SimpleProduct::TYPE);

        if ($product instanceof VariableProduct) {
            $result->setParameter(':bindings', $bindingValues, Connection::PARAM_INT_ARRAY);
        }
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

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('p.id, p.sku')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->innerJoin('pc', self::PRODUCT_TABLE, 'p', 'p.id = pc.child_id');
    }

    private function addBinding(QueryBuilder $qb, AbstractAttribute $bindingAttribute, Language $language): void
    {
        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($bindingAttribute, $language));

        $subQbBinding = $this->connection->createQueryBuilder();
        $subQbBinding->select(sprintf('vt.value as %s', $bindingAttribute->getCode()))
            ->from(self::VALUE_TRANSLATION_TABLE, 'vt')
            ->join('vt', self::PRODUCT_VALUE_TABLE, 'pv', 'vt.id = pv.value_id')
            ->leftJoin('pv', self::LANGUAGE_TREE_TABLE, 'lt', 'lt.code = vt.language')
            ->where(sprintf(
                'pv.product_id = p.id AND pv.attribute_id =  \'%s\' AND lt.lft <= %s AND lt.rgt >= %s',
                $bindingAttribute->getId(),
                $info['lft'],
                $info['rgt']
            ))
            ->orderBy('lft', 'DESC NULLS LAST')
            ->setMaxResults(1);
        $qb->addSelect(sprintf('(%s)', $subQbBinding->getSQL()));
    }
}
