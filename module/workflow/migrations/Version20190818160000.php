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
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS workflow_status (
                    workflow_id UUID NOT NULL,
                    code VARCHAR(128) NOT NULL,   
                    color VARCHAR(7) NOT NULL,
                    name JSONB NOT NULL DEFAULT \'{}\',
                    description JSONB NOT NULL DEFAULT \'{}\',                
                    PRIMARY KEY(workflow_id, code)
                )'
        );

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_CREATE', 'Workflow']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_READ', 'Workflow']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_UPDATE', 'Workflow']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'WORKFLOW_DELETE', 'Workflow']);
    }
}
