<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Migration\AbstractErgonodeMigration;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class
 */
final class Version20180618134343 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');
        $this->addSql(
            'CREATE TABLE importer.import (
                      id UUID NOT NULL,
                      name VARCHAR(128) NOT NULL,
                      type VARCHAR(255) NOT NULL,
                      status VARCHAR(16) NOT NULL,
                      options JSON NOT NULL,
                      reason TEXT DEFAULT NULL,
                      created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                      updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                      started_at TIMESTAMP WITHOUT TIME ZONE,
                      ended_at TIMESTAMP WITHOUT TIME ZONE,
                      PRIMARY KEY(id)
                 )'
        );
        $this->addSql(
            'CREATE TABLE importer.import_line (
                    id UUID NOT NULL,
                    lp BIGSERIAL,
                    import_id UUID NOT NULL,
                    line JSON NOT NULL,
                    created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                    updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                    PRIMARY KEY(id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE importer.event_store (
                    id BIGSERIAL NOT NULL, 
                    aggregate_id uuid NOT NULL, 
                    sequence int, 
                    event character varying(255) NOT NULL, 
                    payload jsonb NOT NULL, 
                    recorded_by uuid default NULL, 
                    recorded_at timestamp without time zone NOT NULL, 
                    CONSTRAINT event_store_pkey PRIMARY KEY (id)
                 )'
        );

        $this->addSql('CREATE INDEX import_line_import_id_idx ON importer.import_line USING btree (import_id)');
        $this->addSql('ALTER TABLE importer.import_line ADD CONSTRAINT import_line_import_id_fk FOREIGN KEY (import_id) REFERENCES importer.import (id) ON DELETE CASCADE');

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'IMPORT_CREATE', 'Import']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'IMPORT_READ', 'Import']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'IMPORT_UPDATE', 'Import']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'IMPORT_DELETE', 'Import']);
    }
}
