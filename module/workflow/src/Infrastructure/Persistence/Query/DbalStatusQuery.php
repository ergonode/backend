<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Persistence\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\Grid\DbalDataSet;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;

/**
 */
class DbalStatusQuery implements StatusQueryInterface
{
    private const STATUS_TABLE = 'public.status';

    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $workflowProvider;

    /**
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $statusRepository;

    /**
     * @param Connection                $connection
     * @param WorkflowProvider          $workflowProvider
     * @param StatusRepositoryInterface $statusRepository
     */
    public function __construct(
        Connection $connection,
        WorkflowProvider $workflowProvider,
        StatusRepositoryInterface $statusRepository
    ) {
        $this->connection = $connection;
        $this->workflowProvider = $workflowProvider;
        $this->statusRepository = $statusRepository;
    }

    /**
     * @param Language $language
     *
     * @return DataSetInterface
     */
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

        return new DbalDataSet($result);
    }

    /**
     * @param Language $language
     *
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
     * @param Language $language
     *
     * @return array
     */
    public function getAllStatuses(language $language): array
    {
        $qb = $this->connection->createQueryBuilder();

        $records = $qb->select(sprintf('id, code, color, name->>\'%s\' as name', $language->getCode()))
            ->from(self::STATUS_TABLE, 'a')
            ->execute()
            ->fetchAll();

        $result = [];
        foreach ($records as $record) {
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
    public function getStatusCount(Language $language): array
    {
        $sql = "SELECT id, code, name->>:language AS label FROM status;";
        $stmt = $this->connection->executeQuery($sql, ['language' => (string) $language]);
        $statuses = $stmt->fetchAll();

        $sql = 'SELECT vt.value, count(pv.product_id)
            FROM attribute a
            JOIN product_value pv ON a.id = pv.attribute_id
            JOIN value_translation vt ON pv.value_id = vt.value_id
            WHERE code = :code
            GROUP BY vt.value
        ';
        $stmt = $this->connection->executeQuery($sql, ['code' => StatusSystemAttribute::CODE]);
        $products = $stmt->fetchAll();

        $result = [];
        foreach ($statuses as $status) {
            $result[$status['code']] = [
                'status_id' => $status['id'],
                'label' => $status['label'],
                'code' => $status['code'],
                'value' => 0,
            ];
        }
        foreach ($products as $product) {
            $result[$product['value']]['value'] = $product['count'];
        }

        return $this->sortStatusesByWorkflowTransitions($result);
    }

    /**
     * @param Language $language
     *
     * @return QueryBuilder
     */
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
        $workflow = $this->workflowProvider->provide();
        $workflowSorted = $this->sortTransitionStatuses($workflow);
        $sorted = [];
        foreach ($workflowSorted as $item) {
            $sorted[] = $statuses[$item];
            unset($statuses[$item]);
        }
        ksort($statuses);

        return array_merge($sorted, array_values($statuses));
    }

    /**
     * @param AbstractWorkflow $workflow
     *
     * @return array
     */
    private function sortTransitionStatuses(AbstractWorkflow $workflow): array
    {
        $transitions = $workflow->getTransitions();

        $defaultStatusCode = $this->getStatusCodeValueById($workflow->getDefaultStatus());
        $code = $defaultStatusCode;
        $sorted = [$code];
        $transitions = new \ArrayIterator($transitions);
        for (; $transitions->valid(); $hit ? $transitions->rewind() : $transitions->next()) {
            $transition = $transitions->current();
            $hit = false;
            $transitionFromCode = $this->getStatusCodeValueById($transition->getFrom());
            $transitionToCode = $this->getStatusCodeValueById($transition->getTo());
            if ($code !== $transitionFromCode) {
                continue;
            }
            // avoids infinite loop
            if ($defaultStatusCode === $transitionToCode) {
                break;
            }
            $code = $sorted[] = $transitionToCode;

            $transitions->offsetUnset($transitions->key());
            $hit = true;
        }

        return $sorted;
    }

    /**
     * @param StatusId $statusId
     *
     * @return string|null
     */
    private function getStatusCodeValueById(StatusId $statusId)
    {
        $status = $this->statusRepository->load($statusId);
        if ($status) {
            return $status->getCode()->getValue();
        }

        return null;
    }
}
