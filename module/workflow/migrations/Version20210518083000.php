<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210518083000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE workflow_transition ADD COLUMN transition_id uuid DEFAULT NULL');
        $this->addSql('ALTER TABLE workflow_transition ADD COLUMN roles jsonb DEFAULT \'{}\'');
        $this->addSql('ALTER TABLE workflow_transition ADD COLUMN condition_set_id uuid DEFAULT NULL');

        $this->addSql('
            UPDATE workflow_transition wt 
            SET transition_id = sq.id::UUID, roles = sq.roles
            FROM(
                SELECT 
                    payload->\'transition\'->>\'id\' AS id, 
                    payload->\'transition\'->>\'from\' AS source_id,  
                    payload->\'transition\'->>\'to\' AS destination_id,
                    payload ->\'transition\'->\'role_ids\' AS roles
                FROM event_store es
                JOIN event_store_class esc ON es.aggregate_id = esc.aggregate_id
                JOIN event_store_event ese ON es.event_id = ese.id
                WHERE esc.class = \'Ergonode\Workflow\Domain\Entity\Workflow\'
                AND	ese.event_class = \'Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent\') AS sq
            WHERE wt.source_id::TEXT = sq.source_id 
            AND wt.destination_id::TEXT = sq.destination_id
        ');

        $this->addSql('
            UPDATE workflow_transition wt 
            SET condition_set_id = sq.condition_set_id::uuid
            FROM(
                SELECT 
                    payload->>\'transition_id\' AS id, 
                    payload->>\'condition_set_id\' AS condition_set_id
                FROM event_store es
                JOIN event_store_class esc ON es.aggregate_id = esc.aggregate_id
                JOIN event_store_event ese ON es.event_id = ese.id
                WHERE esc.class = \'Ergonode\Workflow\Domain\Entity\Workflow\'
                AND	ese.event_class = \'Ergonode\Workflow\Domain\Event\Transition\TransitionConditionSetChangedEvent\'
                ORDER BY es.id ASC
                ) AS sq
            WHERE wt.transition_id::TEXT = sq.id 
        ');

        $this->addSql('
            UPDATE workflow_transition wt 
            SET roles = sq.roles
            FROM(
                SELECT 
                    payload->>\'transition_id\' AS id, 
                    payload->\'role_ids\' AS roles
                FROM event_store es
                JOIN event_store_class esc ON es.aggregate_id = esc.aggregate_id
                JOIN event_store_event ese ON es.event_id = ese.id
                WHERE esc.class = \'Ergonode\Workflow\Domain\Entity\Workflow\'
                AND	ese.event_class = \'Ergonode\Workflow\Domain\Event\Transition\TransitionRoleIdsChangedEvent\'
                ORDER BY es.id ASC
                ) AS sq
            WHERE wt.transition_id::TEXT = sq.id 
        ');

        $this->addSql('ALTER TABLE workflow_transition ALTER COLUMN transition_id DROP DEFAULT');
        $this->addSql('ALTER TABLE workflow_transition ALTER COLUMN transition_id SET NOT NULL');
    }
}
