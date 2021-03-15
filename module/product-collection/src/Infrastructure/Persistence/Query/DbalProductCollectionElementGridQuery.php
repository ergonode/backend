<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionElementGridQueryInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class DbalProductCollectionElementGridQuery implements ProductCollectionElementGridQueryInterface
{
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'product_collection_element';
    private const PUBLIC_PRODUCT_TABLE = 'public.product';

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

    public function getGridQuery(ProductCollectionId $productCollectionId, Language $language): QueryBuilder
    {
        $info = $this->query->getLanguageNodeInfo($language);

        $query = $this->connection->createQueryBuilder();
        $query
            ->select('ce.product_collection_id, ce.product_id as id, ce.visible, ce.created_at, p.sku')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'ce')
            ->join('ce', self::PUBLIC_PRODUCT_TABLE, 'p', 'p.id = ce.product_id')
            ->andWhere($query->expr()->eq('product_collection_id', ':productCollectionId'))
           ->setParameter(':productCollectionId', $productCollectionId->getValue());


        $this->defaultLabelQueryBuilder->addSelect($query, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($query, $info['lft'], $info['rgt']);

        return $query;
    }
}
