<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\Query\Builder\DefaultImageQueryBuilderInterface;
use Ergonode\Core\Domain\Query\Builder\DefaultLabelQueryBuilderInterface;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\Product\Infrastructure\Strategy\ProductAttributeLanguageResolver;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionElementQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductCollectionId;

/**
 */
class DbalProductCollectionElementQuery implements ProductCollectionElementQueryInterface
{
    private const PRODUCT_COLLECTION_ELEMENT_TABLE = 'collection_element';
    private const PUBLIC_PRODUCT_TABLE = 'public.product';
    private const DESIGNER_TEMPLATE_TABLE = 'designer.template';
    private const PUBLIC_PRODUCT_VALUE_TABLE = 'public.product_value';
    private const PUBLIC_VALUE_TRANSLATION = 'public.value_translation';
    private const PUBLIC_LANGUAGE_TREE = 'public.language_tree';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var LanguageQueryInterface
     */
    protected LanguageQueryInterface $query;

    /**
     * @var ProductAttributeLanguageResolver
     */
    protected ProductAttributeLanguageResolver $resolver;

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
     * @param ProductAttributeLanguageResolver  $resolver
     * @param DefaultLabelQueryBuilderInterface $defaultLabelQueryBuilder
     * @param DefaultImageQueryBuilderInterface $defaultImageQueryBuilder
     */
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

    /**
     * @param ProductCollectionId $productCollectionId
     * @param Language            $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductCollectionId $productCollectionId, Language $language): DataSetInterface
    {
        $info = $this->query->getLanguageNodeInfo($language);
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('product_collection_id', ':productCollectionId'));
        $query->join('ce', self::PUBLIC_PRODUCT_TABLE, 'ppt', 'ppt.id = ce.product_id');
        $this->defaultLabelQueryBuilder->addSelect($query, $info['lft'], $info['rgt']);
        $this->defaultImageQueryBuilder->addSelect($query, $info['lft'], $info['rgt']);
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':productCollectionId', $productCollectionId->getValue());

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ce.product_collection_id, ce.product_id as id, ce.visible, ce.created_at, ppt.sku')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'ce');
    }
}
