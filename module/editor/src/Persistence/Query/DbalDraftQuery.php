<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Persistence\Query;

use Doctrine\DBAL\Connection;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Editor\Domain\Query\DraftQueryInterface;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class DbalDraftQuery implements DraftQueryInterface
{
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
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface
    {
        $query = $this->connection->createQueryBuilder();
        $query
            ->select('d.*, p.template_id')
            ->from('designer.draft', 'd')
            ->join('d', 'designer.product', 'p', 'd.product_id = p.product_id');

        return new DbalDataSet($query);
    }

    /**
     * @param ProductDraftId $draftId
     * @param Language       $language
     *
     * @return array
     */
    public function getDraftView(ProductDraftId $draftId, Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('d.id AS draft_id, p.id AS product_id, pd.template_id, p.sku')
            ->from('designer.draft', 'd')
            ->join('d', 'product', 'p', 'p.id = d.product_id')
            ->join('d', 'designer.product', 'pd', 'pd.product_id = d.product_id')
            ->where($qb->expr()->eq('d.id', ':id'))
            ->setParameter(':id', $draftId->getValue())
            ->execute()
            ->fetch();

        $result['language'] = $language->getCode();
        $result['values'] = $this->getDraftValues($draftId, $language);
        $result['category_ids'] = $this->getCategories($draftId);

        return $result;
    }

    /**
     * @param ProductId $productId
     *
     * @return ProductDraftId|null
     */
    public function getActualDraftId(ProductId $productId): ?ProductDraftId
    {
        $qb = $this->connection->createQueryBuilder();
        $result = $qb->select('id')
            ->from('designer.draft')
            ->andWhere($qb->expr()->eq('product_id', ':productId'))
            ->andWhere($qb->expr()->eq('applied', ':applied'))
            ->setParameter(':productId', $productId->getValue())
            ->setParameter(':applied', false, \PDO::PARAM_BOOL)
            ->setMaxResults(1)
            ->execute()
            ->fetchColumn();

        if ($result) {
            return new ProductDraftId($result);
        }

        return null;
    }

    /**
     * @param ProductDraftId $draftId
     * @param Language       $language
     *
     * @return array
     */
    private function getDraftValues(ProductDraftId $draftId, Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb
            ->select('dv.element_id AS id, dv.value')
            ->from('designer.draft_value', 'dv')
            ->where($qb->expr()->eq('dv.draft_id', ':draftId'))
            ->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('dv.language', ':language'),
                    $qb->expr()->isNull('dv.language')
                )
            )
            ->setParameter(':draftId', $draftId->getValue())
            ->setParameter(':language', $language->getCode())
            ->execute()
            ->fetchAll();
    }

    /**
     * @param ProductDraftId $draftId
     *
     * @return array
     */
    private function getCategories(ProductDraftId $draftId): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('category_id')
            ->from('product_category_product', 'pcp')
            ->join('pcp', 'designer.draft', 'd', 'd.product_id = pcp.product_id')
            ->where($qb->expr()->eq('d.id', ':id'))
            ->setParameter(':id', $draftId->getValue())
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }
}
