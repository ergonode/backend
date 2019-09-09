<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20190818160000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS status (
                id UUID NOT NULL,
                code VARCHAR(128) NOT NULL,   
                color VARCHAR(7) NOT NULL,
                name JSONB NOT NULL DEFAULT \'{}\',
                description JSONB NOT NULL DEFAULT \'{}\',                
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_CREATE', 'Workflow']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_READ', 'Workflow']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_UPDATE', 'Workflow']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_DELETE', 'Workflow']);

        $this->createEventStoreEvents([
            'Ergonode\Workflow\Domain\Event\Status\StatusColorChangedEvent' => 'Status color changed',
            'Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent' => 'Status created',
            'Ergonode\Workflow\Domain\Event\Status\StatusDeletedEvent' => 'Status deleted',
            'Ergonode\Workflow\Domain\Event\Status\StatusDescriptionChangedEvent' => 'Status description changed',
            'Ergonode\Workflow\Domain\Event\Status\StatusNameChangedEvent' => 'Status name changed',
            'Ergonode\Workflow\Domain\Event\Workflow\WorkflowCreatedEvent' => 'Workflow created',
            'Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusAddedEvent' => 'Added status to workflow',
            'Ergonode\Workflow\Domain\Event\Workflow\WorkflowStatusRemovedEvent' => 'Deleted status from workflow',
            'Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionAddedEvent' => 'Added transition to workflow',
            'Ergonode\Workflow\Domain\Event\Workflow\WorkflowTransitionRemovedEvent' => 'Deleted transition from workflow',
            'Ergonode\Workflow\Domain\Event\Workflow\WorkflowDeletedEvent' => 'Workflow deleted',
        ]);
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createEventStoreEvents(array $collection): void
    {
        foreach ($collection as $class => $translation) {
            $this->connection->insert('event_store_event', [
                'id' => Uuid::uuid4()->toString(),
                'event_class' => $class,
                'translation_key' => $translation,
            ]);
        }
    }
}
