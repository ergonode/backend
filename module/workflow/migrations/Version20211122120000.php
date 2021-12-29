<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211122120000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $eventId = $this->connection->executeQuery(
            'SELECT id FROM event_store_event WHERE event_class = :class',
            [
                'class' => WorkflowTransitionRemovedEvent::class,
            ]
        )->fetchOne();

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
            'UPDATE event_store SET payload = jsonb_insert(payload,\'{from}\', payload->\'source\') 
                 WHERE event_id = :id',
            [':id' => $eventId]
        );
        $this->addSql(
            'UPDATE event_store SET payload = jsonb_insert(payload,\'{to}\', payload->\'destination\') 
                 WHERE event_id = :id',
            [':id' => $eventId]
        );
        $this->addSql(
            'UPDATE event_store SET payload  = payload #- \'{source}\' WHERE event_id = :id',
            [':id' => $eventId]
        );
        $this->addSql(
            'UPDATE event_store SET payload  = payload #- \'{destination}\' WHERE event_id = :id',
            [':id' => $eventId]
        );

        $this->addSql('DELETE FROM event_store_snapshot WHERE aggregate_id in (SELECT id FROM workflow)');
    }
}
