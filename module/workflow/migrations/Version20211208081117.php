<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211208081117 extends AbstractErgonodeMigration
{
    private array $status = [];
    private array $workflow = [];
    private array $transition = [];

    public function up(Schema $schema): void
    {
        $workflowId = $this->connection->executeQuery(
            'SELECT es.aggregate_id 
                FROM event_store es 
                JOIN event_store_event ese ON ese.id = es.event_id
                WHERE ese.event_class = :class',
            [
                'class' => WorkflowCreatedEvent::class,
            ]
        )->fetchOne();

        if (false !== $workflowId && Uuid::isValid($workflowId)) {
            return;
        }

        $recordedAt = new \DateTime('now');

        $this->insertStatuses($recordedAt);
        $this->insertWorkflow($recordedAt);
        $this->insertTransition($recordedAt);
    }

    private function insertTransition(\DateTime $recordedAt): void
    {
        $eventCreateId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => WorkflowTransitionAddedEvent::class,
            ]
        )->fetchOne();

        $transition = $this->getTransition();

        $this->addEvent(
            $transition['id'],
            2,
            $eventCreateId,
            $transition,
            $recordedAt
        );

        $this->addSql(
            'INSERT INTO workflow_transition (workflow_id, from_id, to_id, transition_id, roles, condition_set_id)
                VALUES(:workflowId, :fromId, :toId, :transitionId, :roles, :conditionSetId)',
            [
                'workflowId' => $transition['id'],
                'fromId' => $transition['transition']['from'],
                'toId' => $transition['transition']['to'],
                'transitionId' => $transition['transition']['id'],
                'roles' => '{}',
                'conditionSetId' => null,
            ]
        );
    }

    private function insertWorkflow(\DateTime $recordedAt): void
    {
        $eventCreateId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => WorkflowCreatedEvent::class,
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

    private function insertStatuses(\DateTime $recordedAt): void
    {
        $eventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => StatusCreatedEvent::class,
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

    private function getTransition(): array
    {
        if (!empty($this->transition)) {
            return $this->transition;
        }

        $this->transition = [
            'id' => $this->getWorkflow()['id'],
            'transition' => [
                'id' => Uuid::uuid4()->toString(),
                'to' => $this->getStatus()['draft']['id'],
                'from' => $this->getStatus()['new']['id'],
                'role_ids' => [],
                'aggregate_root' => null,
                'condition_set_id' => null,
            ],
        ];

        return $this->transition;
    }
}
