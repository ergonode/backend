<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Persistence\Dbal\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
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

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductCollectionId $productCollectionId
     * @param Language            $language
     *
     * @return DataSetInterface
     */
    public function getDataSet(ProductCollectionId $productCollectionId, Language $language): DataSetInterface
    {
        $query = $this->getQuery();
        $query->andWhere($query->expr()->eq('product_collection_id', ':productCollectionId'));
        $query->addSelect('ce.created_at, pvtdt.value as system_name, sku, pvtdi.value as default_image');
        $query->join('ce', self::PUBLIC_PRODUCT_TABLE, 'ppt', 'ppt.id = ce.product_id');
        $query->join('ppt', self::DESIGNER_TEMPLATE_TABLE, 'dtt', 'ppt.template_id = dtt.id');
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
            sprintf(
                '(pvtdt.language = \'%s\' OR pvtdt.language IS NULL) AND ppvtdt.value_id = pvtdt.value_id',
                $language->getCode()
            )
        );

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
            ->select('ce.product_collection_id, ce.product_id as id, ce.visible')
            ->from(self::PRODUCT_COLLECTION_ELEMENT_TABLE, 'ce');
    }
}
