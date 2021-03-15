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
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\ProductChildrenQueryInterface;

class DbalProductChildrenQuery implements ProductChildrenQueryInterface
{
    private const PRODUCT_CHILDREN_TABLE = 'public.product_children';

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
     * @return ProductId[]
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
}
