<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Completeness\Domain\Query\CompletenessQueryInterface;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Entity\ProductDraftId;

/**
 */
class DbalCompletenessQuery implements CompletenessQueryInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param ProductDraftId $draftId
     * @param Language       $language
     *
     * @return CompletenessReadModel
     */
    public function getCompleteness(ProductDraftId $draftId, Language $language): CompletenessReadModel
    {
        $model = new CompletenessReadModel($language);
        $qb = $this->getQuery();
        $records = $qb
            ->where($qb->expr()->eq('d.id', ':id'))
            ->setParameter(':id', $draftId->getValue())
            ->setParameter(':language', $language->getCode())
            ->execute()
            ->fetchAll();

        foreach ($records as $record) {
            $model->addField(new AttributeId($record['id']), $record['name'], $record['required'], $record['value']);
        }

        return $model;
    }

    /**
     * @return QueryBuilder
     */
    private function getQuery(): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select('te.element_id AS id, te.required, dv.language, dv.value, COALESCE(el.value, e.code) AS name')
            ->from('designer.draft', 'd')
            ->innerJoin('d', 'designer.product', 'p', 'p.product_id = d.product_id')
            ->innerJoin('p', 'designer.template_element', 'te', 'te.template_id = p.template_id')
            ->innerJoin('te', 'designer.element', 'e', 'e.id = te.element_id')
            ->leftJoin('e', 'designer.element_label', 'el', 'el.element_id = e.id AND el.language = :language')
            ->leftJoin('te', 'designer.draft_value', 'dv', 'd.id = dv.draft_id AND dv.element_id = te.element_id AND (dv.language = :language OR dv.language IS NULL)');
    }
}
