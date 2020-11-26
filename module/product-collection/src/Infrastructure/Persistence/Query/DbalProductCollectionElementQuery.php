<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionElementQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

class DbalProductCollectionElementQuery implements ProductCollectionElementQueryInterface
{
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'product_collection_element';
    private const PUBLIC_PRODUCT_TABLE = 'public.product';

    private Connection $connection;

    protected LanguageQueryInterface $query;

    protected DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder;

    protected DefaultImageQueryBuilderInterface $defaultImageQueryBuilder;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(
        Connection $connection,
        LanguageQueryInterface $query,
        DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder,
        DefaultImageQueryBuilderInterface $defaultImageQueryBuilder,
        DbalDataSetFactory $dataSetFactory
    ) {
        $this->connection = $connection;
        $this->query = $query;
        $this->defaultLabelQueryBuilder = $defaultLabelQueryBuilder;
        $this->defaultImageQueryBuilder = $defaultImageQueryBuilder;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(ProductCollectionId $productCollectionId, Language $language): DataSetInterface
    {
        $info = $this->query->getLanguageNodeInfo($language);
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('product_collection_id', ':productCollectionId'));
        $this->defaultLabelQueryBuilder->addSelect($query, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($query, $info['lft'], $info['rgt']);
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':productCollectionId', $productCollectionId->getValue());

        return $this->dataSetFactory->create($result);
    }

    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ce.product_collection_id, ce.product_id as id, ce.visible, ce.created_at, p.sku')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'ce')
            ->join('ce', self::PUBLIC_PRODUCT_TABLE, 'p', 'p.id = ce.product_id');
    }
}
