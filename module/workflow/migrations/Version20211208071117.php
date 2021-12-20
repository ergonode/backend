<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent;
use Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionConditionsChangedEvent;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211208071117 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $conditionSetIds = $this->getConditionSetIds();

        if (!empty($conditionSetIds)) {
            $this->deleteEvents($conditionSetIds);
            $this->deleteSnapshot($conditionSetIds);


            $this->addSql(
                '
                DELETE
                FROM condition_set 
                WHERE id IN (:aggregateIds)
                ',
                [
                    'aggregateIds' => $conditionSetIds,
                ],
                [
                    'aggregateIds' => Connection::PARAM_STR_ARRAY,
                ]
            );
        }

        $this->addSql(
            '
                DELETE
                FROM event_store
                WHERE event_id IN (
                    SELECT
                           id
                    FROM event_store_event 
                    WHERE event_class IN (:event)
                    )
                ',
            [
                'event' => [
                    WorkflowTransitionAddedEvent::class,
                    WorkflowTransitionRemovedEvent::class,
                    TransitionConditionSetChangedEvent::class,
                    TransitionRoleIdsChangedEvent::class,

                ],
            ],
            [
                'event' => Connection::PARAM_STR_ARRAY,
            ]
        );

        $this->addSql(
            '
                DELETE
                FROM workflow_transition
                WHERE workflow_id IS NOT NULL
                ',
        );

        $this->addSql(
            '
                DELETE
                FROM event_store_snapshot 
                WHERE aggregate_id IN (SELECT id FROM workflow)
                ',
        );

        $this->createEventStoreEvents([
            WorkflowTransitionConditionsChangedEvent::class => 'Change transition conditions',
        ]);
    }

    private function getConditionSetIds(): array
    {
        return $this->connection
            ->executeQuery(
                'SELECT
                    es.payload->>\'condition_set_id\' as condition_set_id
                FROM event_store es
                JOIN event_store_event ese  on ese.id = es.event_id
                WHERE ese.event_class IN (:event) AND  es.payload->>\'condition_set_id\' is not null
                ',
                [
                    'event' => [TransitionConditionSetChangedEvent::class, WorkflowTransitionAddedEvent::class],
                ],
                [
                    'event' => Connection::PARAM_STR_ARRAY,
                ]
            )->fetchFirstColumn();
    }

    /**
     * @param string[]
     */
    private function deleteEvents(array $ids): void
    {
        $this->addSql(
            '
                DELETE
                FROM event_store 
                WHERE aggregate_id IN (:aggregateIds)
                ',
            [
                'aggregateIds' => $ids,
            ],
            [
                'aggregateIds' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }

    /**
     * @param string[]
     */
    private function deleteSnapshot(array $ids): void
    {
        $this->addSql(
            '
                DELETE
                FROM event_store_snapshot 
                WHERE aggregate_id IN (:aggregateIds)
                ',
            [
                'aggregateIds' => $ids,
            ],
            [
                'aggregateIds' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->addSql(
                'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
                [Uuid::uuid4()->toString(), $class, $translation]
            );
        }
    }
}
