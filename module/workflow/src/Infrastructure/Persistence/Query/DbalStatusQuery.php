<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\Factory\DbalDataSetFactory;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;

class DbalStatusQuery implements StatusQueryInterface
{
    private const STATUS_TABLE = 'public.status';

    private Connection $connection;

    private WorkflowProvider $workflowProvider;

    private DbalDataSetFactory $dataSetFactory;

    public function __construct(
        Connection $connection,
        WorkflowProvider $workflowProvider,
        DbalDataSetFactory $dataSetFactory
    ) {
        $this->connection = $connection;
        $this->workflowProvider = $workflowProvider;
        $this->dataSetFactory = $dataSetFactory;
    }

    public function getDataSet(Language $language): DataSetInterface
    {
        $query = $this->getQuery($language);
        $query->addSelect(
            '(SELECT CASE WHEN count(*) > 0 THEN true ELSE false END FROM workflow w WHERE '.
            ' w.default_status = a.id AND w.code =\'default\')::BOOLEAN AS is_default '
        );

        $result = $this->connection->createQueryBuilder();
        $result->select('*');
        $result->from(sprintf('(%s)', $query->getSQL()), 't');

        return $this->dataSetFactory->create($result);
    }

    /**
     * @return array
     */
    public function getDictionary(Language $language): array
    {
        return $this->getQuery($language)
            ->select('id, code')
            ->orderBy('name', 'desc')
            ->execute()
            ->fetchAll(\PDO::FETCH_KEY_PAIR);
    }

    /**
     * @return array
     */
    public function getAllStatuses(Language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb->select(sprintf('id, code, color, name->>\'%s\' as name', $language->getCode()))
            ->from(self::STATUS_TABLE, 'a')
            ->execute()
            ->fetchAll();

        $result = [];
        foreach ($records as $record) {
            $result[$record['id']]['code'] = $record['code'];
            $result[$record['id']]['color'] = $record['color'];
            $result[$record['id']]['name'] = $record['name'];
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getAllCodes(): array
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('code')
            ->from(self::STATUS_TABLE, 'a')
            ->execute()
            ->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCount(Language $translationLanguage, Language $workflowLanguage): array
    {
        $sql = 'SELECT 
            s.code, 
            s.name->>:translationLanguage AS label,
            s.id AS status_id,
            count(pws.product_id) AS value,
            s.color
            FROM status s
            JOIN product_workflow_status pws ON s.id = pws.status_id
            WHERE pws.language = :workflowLanguage
            GROUP BY s.id, s.code, label
            UNION
            SELECT s.code, s.name->>:translationLanguage AS label, s.id, 0 AS value, s.color FROM status s
        ';
        $stmt = $this->connection->executeQuery(
            $sql,
            [
                'translationLanguage' => (string) $translationLanguage,
                'workflowLanguage' => (string) $workflowLanguage,
            ],
        );
        $statuses = $stmt->fetchAll();

        $result = [];
        foreach ($statuses as $status) {
            $result[$status['status_id']] = $result[$status['status_id']]['value'] ?? 0 ?
                    $result[$status['status_id']] :
                    $status;
        }

        return $this->sortStatusesByWorkflowTransitions($result);
    }

    private function getQuery(Language $language): QueryBuilder
    {
        return $this->connection->createQueryBuilder()
            ->select(sprintf(
                'id, code, id AS status, color, name->>\'%s\' as name, description->>\'%s\' as description',
                $language->getCode(),
                $language->getCode()
            ))
            ->from(self::STATUS_TABLE, 'a');
    }

    /**
     * @param mixed[][] $statuses
     *
     * @return mixed[][]
     */
    private function sortStatusesByWorkflowTransitions(array $statuses): array
    {
        $workflowSorted = $this->workflowProvider->provide()->getSortedTransitionStatuses();
        $sorted = [];
        foreach ($workflowSorted as $item) {
            $sorted[] = $statuses[$item->getValue()];
            unset($statuses[$item->getValue()]);
        }
        usort(
            $statuses,
            fn(array $a, array $b) => strcmp($a['code'], $b['code']),
        );

        return array_merge($sorted, array_values($statuses));
    }
}
