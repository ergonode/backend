<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionConditionsChangedEvent;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211122120000 extends AbstractErgonodeMigration
{
    private array $status = [];
    private array $workflow = [];

    public function up(Schema $schema): void
    {
        $recordedAt = new \DateTime('now');

        $this->cleanConditionSet();
        $this->cleanTransition();
        $this->createWorkflow($recordedAt);
        $this->cleanAfter();


        $this->addNewEvent();
        $this->addNewPrivilege();
    }

    private function cleanConditionSet(): void
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
    }

    private function cleanTransition(): void
    {
        $this->addSql('ALTER TABLE workflow_transition DROP CONSTRAINT status_workflow_transition_source_fk ');
        $this->addSql('ALTER TABLE workflow_transition DROP CONSTRAINT status_workflow_transition_destination_fk ');

        $this->addSql('ALTER TABLE public.workflow_transition RENAME COLUMN source_id TO from_id');
        $this->addSql('ALTER TABLE public.workflow_transition RENAME COLUMN destination_id TO to_id');

        $this->addSql(
            'ALTER TABLE workflow_transition
                    ADD CONSTRAINT status_workflow_transition_from_fk FOREIGN KEY (from_id) 
                    REFERENCES status(id) ON DELETE CASCADE ON UPDATE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE workflow_transition
                    ADD CONSTRAINT status_workflow_transition_to_fk FOREIGN KEY (to_id) 
                    REFERENCES status(id) ON DELETE CASCADE ON UPDATE CASCADE'
        );

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
                    'Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent',
                    'Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent',
                    'Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent',
                    'Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent',

                ],
            ],
            [
                'event' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }

    private function createWorkflow(\DateTime $recordedAt): void
    {
        $workflowId = $this->connection->executeQuery(
            'SELECT es.aggregate_id 
                FROM event_store es 
                JOIN event_store_event ese ON ese.id = es.event_id
                WHERE ese.event_class = :class',
            [
                'class' => 'Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent',
            ]
        )->fetchOne();

        if (false !== $workflowId && Uuid::isValid($workflowId)) {
            return;
        }

        $this->insertStatuses($recordedAt);
        $this->insertWorkflow($recordedAt);
    }

    private function cleanAfter(): void
    {
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

    private function addEvent(
        string $aggregateId,
        int $sequence,
        string $eventId,
        array $payload,
        \DateTime $recordedAt
    ): void {
        $this->addSql(
            'INSERT INTO event_store (aggregate_id, sequence, event_id, payload,recorded_at) 
                    VALUES (:aggregateId,:sequence, :eventId, :payload, :recordedAt)',
            [
                'aggregateId' => $aggregateId,
                'sequence' => $sequence,
                'eventId' => $eventId,
                'payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'recordedAt' => $recordedAt->format('Y-m-d H:i:s.u'),
            ]
        );
    }

    private function addAggregateClass(string $aggregateId, string $class): void
    {
        $this->addSql(
            'INSERT INTO event_store_class (aggregate_id, class) 
                    VALUES (:aggregateId, :class)',
            [
                'aggregateId' => $aggregateId,
                'class' => $class,
            ]
        );
    }

    /**
     * @return string[]
     */
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
                    'event' => [
                        'Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent',
                        'Ergonode\Workflow\Domain\Event\Transition\WorkflowTransitionAddedEvent',
                    ],
                ],
                [
                    'event' => Connection::PARAM_STR_ARRAY,
                ]
            )->fetchFirstColumn();
    }

    private function insertStatuses(\DateTime $recordedAt): void
    {
        $eventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => 'Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent',
            ]
        )->fetchOne();

        foreach ($this->getStatus() as $status) {
            $this->addEvent(
                $status['id'],
                1,
                $eventId,
                $status,
                $recordedAt
            );
            $this->addAggregateClass($status['id'], Status::class);

            $this->addSql(
                'INSERT INTO status (id, code, color, "name", description)
                VALUES(:id, :code, :color, :name, :description)',
                [
                    'id' => $status['id'],
                    'code' => $status['code'],
                    'color' => $status['color'],
                    'name' => json_encode($status['name'], JSON_UNESCAPED_UNICODE),
                    'description' => json_encode($status['description'], JSON_UNESCAPED_UNICODE),
                ]
            );
        }
    }

    private function insertWorkflow(\DateTime $recordedAt): void
    {
        $eventCreateId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => 'Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent',
            ]
        )->fetchOne();

        $workflow = $this->getWorkflow();

        $this->addEvent(
            $workflow['id'],
            1,
            $eventCreateId,
            $workflow,
            $recordedAt
        );
        $this->addAggregateClass($workflow['id'], Workflow::class);

        $this->addSql(
            'INSERT INTO workflow (id, code, default_status)
                VALUES(:id, :code, :defaultStatus)',
            [
                'id' => $workflow['id'],
                'code' => $workflow['code'],
                'defaultStatus' => $this->getStatus()['new']['id'],
            ]
        );
    }

    private function getStatus(): array
    {
        if (!empty($this->status)) {
            return $this->status;
        }

        $this->status = [
            'new' => [
                'id' => Uuid::uuid4()->toString(),
                'code' => 'new',
                'name' => [
                    'en_GB' => 'New',
                    'pl_PL' => 'Nowy',
                ],
                'color' => '#33373E',
                'description' => [
                    'en_GB' => 'New',
                    'pl_PL' => 'Nowy',
                ],
            ],
            'draft' => [
                'id' => Uuid::uuid4()->toString(),
                'code' => 'draft',
                'name' => [
                    'en_GB' => 'Draft',
                    'pl_PL' => 'Szkic',
                ],
                'color' => '#FFC108',
                'description' => [
                    'en_GB' => 'Draft',
                    'pl_PL' => 'Szkic',
                ],
            ],
            'to_approve' => [
                'id' => Uuid::uuid4()->toString(),
                'code' => 'to approve',
                'name' => [
                    'en_GB' => 'To approve',
                    'pl_PL' => 'Do akceptacji',
                ],
                'color' => '#AA00FF',
                'description' => [
                    'en_GB' => 'To approve',
                    'pl_PL' => 'Do akceptacji',
                ],
            ],
            'ready_to_publish' => [
                'id' => Uuid::uuid4()->toString(),
                'code' => 'ready to publish',
                'name' => [
                    'en_GB' => 'Ready to publish',
                    'pl_PL' => 'Gotowy do publikacji',
                ],
                'color' => '#43A047',
                'description' => [
                    'en_GB' => 'Ready to publish',
                    'pl_PL' => 'Gotowy do publikacji',
                ],
            ],
            'to_correct' => [
                'id' => Uuid::uuid4()->toString(),
                'code' => 'to correct',
                'name' => [
                    'en_GB' => 'To correct',
                    'pl_PL' => 'Do poprawy',
                ],
                'color' => '#C62828',
                'description' => [
                    'en_GB' => 'To correct',
                    'pl_PL' => 'Do poprawy',
                ],
            ],
            'published' => [
                'id' => Uuid::uuid4()->toString(),
                'code' => 'published',
                'name' => [
                    'en_GB' => 'Published',
                    'pl_PL' => 'Opublikowany',
                ],
                'color' => '#2096F3',
                'description' => [
                    'en_GB' => 'Published',
                    'pl_PL' => 'Opublikowany',
                ],
            ],
        ];

        return $this->status;
    }

    private function getWorkflow(): array
    {
        if (!empty($this->workflow)) {
            return $this->workflow;
        }

        $this->workflow = [
            'id' => Uuid::uuid4()->toString(),
            'code' => 'default',
            'class' => Workflow::class,
            'statuses' => [
                $this->getStatus()['new']['id'],
                $this->getStatus()['draft']['id'],
                $this->getStatus()['to_approve']['id'],
                $this->getStatus()['ready_to_publish']['id'],
                $this->getStatus()['to_correct']['id'],
                $this->getStatus()['published']['id'],
            ],
        ];

        return $this->workflow;
    }

    private function addNewEvent(): void
    {
        $this->addSql(
            'INSERT INTO event_store_event (id, event_class, translation_key) VALUES (?,?,?)',
            [
                Uuid::uuid4()->toString(),
                WorkflowTransitionConditionsChangedEvent::class,
                'Change transition conditions',
            ]
        );
    }

    private function addNewPrivilege(): void
    {
        $this->insertEndpointPrivileges(
            [
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION_DICTIONARY',
                'ERGONODE_ROLE_WORKFLOW_PUT_TRANSITION_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_TRANSITION_CONDITION',
            ]
        );

        $this->insertPrivileges(
            'WORKFLOW_READ',
            [
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION_DICTIONARY',
                'ERGONODE_ROLE_WORKFLOW_GET_TRANSITION_CONDITION',
            ]
        );

        $this->insertPrivileges(
            'WORKFLOW_CREATE',
            [
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION_DICTIONARY',
                'ERGONODE_ROLE_WORKFLOW_PUT_TRANSITION_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_TRANSITION_CONDITION',
            ]
        );

        $this->insertPrivileges(
            'WORKFLOW_UPDATE',
            [
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_CONDITION_DICTIONARY',
                'ERGONODE_ROLE_WORKFLOW_PUT_TRANSITION_CONDITION',
                'ERGONODE_ROLE_WORKFLOW_GET_TRANSITION_CONDITION',
            ]
        );
    }

    /**
     * @param string[] $privileges
     */
    private function insertEndpointPrivileges(array $privileges): void
    {
        foreach ($privileges as $privilege) {
            $this->addSql(
                'INSERT INTO privileges_endpoint (id, name) VALUES (?, ?)',
                [Uuid::uuid4()->toString(), $privilege]
            );
        }
    }

    /**
     * @param string[] $endpoints
     */
    private function insertPrivileges(string $privilege, array $endpoints): void
    {
        $this->addSql(
            'INSERT INTO privileges_endpoint_privileges (privileges_id, privileges_endpoint_id)
                    SELECT p.id, pe.id 
                    FROM privileges_endpoint pe, "privileges" p 
                    WHERE p.code = :privilege
                    AND pe."name" IN(:endpoints)
            ',
            [
                ':privilege' => $privilege,
                ':endpoints' => $endpoints,
            ],
            [
                ':endpoints' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }
}
