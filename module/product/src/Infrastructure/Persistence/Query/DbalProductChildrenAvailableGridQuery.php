<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use Ergonode\Product\Domain\Query\ProductChildrenAvailableGridQueryInterface;

class DbalProductChildrenAvailableGridQuery implements ProductChildrenAvailableGridQueryInterface
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

    /**
     * @param AbstractAttribute[] $bindings
     */
    public function getGridQuery(AbstractAssociatedProduct $product, Language $language, array $bindings): QueryBuilder
    {
        $info = $this->query->getLanguageNodeInfo($language);

        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id, p.sku, p.template_id')
            ->addSelect(sprintf('(SELECT EXISTS(%s) as attached)', $this->getSubQuery()->getSQL()))
            ->from(self::PRODUCT_TABLE, 'p')
            ->join('p', self::PRODUCT_VALUE_TABLE, 'pv', 'p.id = pv.product_id')
            ->where('p.type = :type')
            ->groupBy('p.id, p.sku, p.template_id')
            ->having($qb->expr()->gt('count(*)', ':count'))
            ->setParameter(':id', $product->getId()->getValue())
            ->setParameter(':type', SimpleProduct::TYPE)
            ->setParameter(':count', 0);

        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);

        if ($product instanceof VariableProduct) {
            $bindingValues = [];
            foreach ($bindings as $binding) {
                $bindingValues[] = $binding->getId()->getValue();
                $qb->addSelect(sprintf('(%s)', $this->getBindingQuery($binding, $language)->getSQL()));
            }
            $count = count($bindingValues) - 1;
            $qb
                ->andWhere($qb->expr()->in('pv.attribute_id', ':bindings'))
                ->setParameter(':bindings', $bindingValues, Connection::PARAM_INT_ARRAY)
                ->setParameter(':count', $count);
        }

        return $qb;
    }

    private function getSubQuery(): QueryBuilder
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select('pc.child_id')
            ->from(self::PRODUCT_CHILDREN_TABLE, 'pc')
            ->where($query->expr()->eq('pc.product_id', ':id'))
            ->andWhere($query->expr()->eq('pc.child_id', 'p.id'));
    }

    private function getBindingQuery(AbstractAttribute $bindingAttribute, Language $language): QueryBuilder
    {
        $info = $this->query->getLanguageNodeInfo($this->resolver->resolve($bindingAttribute, $language));

        $qb = $this->connection->createQueryBuilder();
        $qb->select(sprintf('vt.value as %s', $bindingAttribute->getCode()))
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

        return $qb;
    }
}
