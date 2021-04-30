<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Product\Domain\Query\ProductRelationAttributeGridQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class DbalProductRelationAttributeGridQuery implements ProductRelationAttributeGridQueryInterface
{
    private const PRODUCT_TABLE = 'public.product';

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

    public function getGridQuery(
        AbstractProduct $product,
        ProductRelationAttribute $attribute,
        Language $language
    ): QueryBuilder {
        $value = '';
        if ($product->hasAttribute($attribute->getCode())) {
            $translation = $product->getAttribute($attribute->getCode())->getValue();
            if (array_key_exists($language->getCode(), $translation)) {
                $value = $translation[$language->getCode()] ?: '';
            }
        }

        $info = $this->query->getLanguageNodeInfo($language);

        $qb = $this->connection->createQueryBuilder();
        $qb->select('p.id, p.sku, p.template_id')
            ->addSelect('p.id::TEXT = ANY (regexp_split_to_array(:string, \',\')) AS attached')
            ->from(self::PRODUCT_TABLE, 'p')
            ->andWhere($qb->expr()->neq('p.id', ':product_id'))
            ->setParameter(':product_id', $product->getId()->getValue())
            ->setParameter(':string', $value);

        $this->defaultLabelQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($qb, $info['lft'], $info['rgt']);

        return $qb;
    }
}
