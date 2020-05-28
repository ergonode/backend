<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
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
    private const DESIGNER_PRODUCT_TABLE = 'designer.product';
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
     * @param Connection                       $connection
     * @param LanguageQueryInterface           $query
     * @param ProductAttributeLanguageResolver $resolver
     */
    public function __construct(
        Connection $connection,
        LanguageQueryInterface $query,
        ProductAttributeLanguageResolver $resolver
    ) {
        $this->connection = $connection;
        $this->query = $query;
        $this->resolver = $resolver;
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
        $limit = $this->getLimit($productCollectionId);

        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('product_collection_id', ':productCollectionId'));
        $query->andWhere(
            sprintf('(pltdi.lft <= %s AND pltdi.rgt >= %s) OR pltdi.lft IS NULL', $info['lft'], $info['rgt'])
        );
        $query->andWhere(
            sprintf('(pltdt.lft <= %s AND pltdt.rgt >= %s) OR pltdt.lft IS NULL', $info['lft'], $info['rgt'])
        );
        $query->addSelect(
            'created_at,
         CASE
           WHEN dtt.default_text IS NULL THEN ppt.sku::VARCHAR
           WHEN dtt.default_text IS NOT NULL THEN pvtdt.value::VARCHAR
           END as system_name,
            sku, 
            pvtdi.value as default_image'
        );
        $query->join('ce', self::PUBLIC_PRODUCT_TABLE, 'ppt', 'ppt.id = ce.product_id');
        $query->join('ce', self::DESIGNER_PRODUCT_TABLE, 'dpt', 'dpt.product_id = ce.product_id');
        $query->join('dpt', self::DESIGNER_TEMPLATE_TABLE, 'dtt', 'dpt.template_id = dtt.id');
        $query->leftJoin(
            'dtt',
            self::PUBLIC_PRODUCT_VALUE_TABLE,
            'ppvtdi',
            'ppvtdi.product_id = ce.product_id AND ppvtdi.attribute_id = dtt.default_image'
        );
        $query->leftJoin(
            'ppvtdi',
            self::PUBLIC_VALUE_TRANSLATION,
            'pvtdi',
            'ppvtdi.value_id = pvtdi.value_id'
        );
        $query->leftJoin(
            'dtt',
            self::PUBLIC_PRODUCT_VALUE_TABLE,
            'ppvtdt',
            'ppvtdt.product_id = ce.product_id AND ppvtdt.attribute_id = dtt.default_text'
        );
        $query->leftJoin(
            'ppvtdt',
            self::PUBLIC_VALUE_TRANSLATION,
            'pvtdt',
            'ppvtdt.value_id = pvtdt.value_id',
        );
        $query->leftJoin(
            'pvtdt',
            self::PUBLIC_LANGUAGE_TREE,
            'pltdt',
            'pltdt.code = pvtdt.language'
        );
        $query->leftJoin(
            'pvtdi',
            self::PUBLIC_LANGUAGE_TREE,
            'pltdi',
            'pltdi.code = pvtdi.language'
        );
        $query->orderBy('pltdt.lft', 'DESC');
        $query->setMaxResults($limit);
        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');
        $result->setParameter(':productCollectionId', $productCollectionId->getValue());
        $result->setParameter(':lft', $info['lft']);
        $result->setParameter(':rgt', $info['rgt']);

        return new DbalDataSet($result);
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('ce.product_collection_id, ce.product_id as id, ce.visible')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'ce');
    }

    /**
     * @param ProductCollectionId $productCollectionId
     *
     * @return int | null
     */
    private function getLimit(ProductCollectionId $productCollectionId): ?int
    {
        $query = $this->connection->createQueryBuilder();

        return $query
            ->select('COUNT(ce.product_id)  ')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'ce')
            ->where('ce.product_collection_id = :productCollectionId')
            ->setParameter(':productCollectionId', $productCollectionId->getValue())
            ->execute()
            ->fetch(\PDO::FETCH_COLUMN);
    }
}
