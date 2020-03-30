<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180618134343 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');

        $this->addSql('
            CREATE TABLE importer.source (
                id UUID NOT NULL,
                name VARCHAR(255) NOT NULL,                  
                type VARCHAR(255) NOT NULL,
                class VARCHAR(255) NOT NULL,                       
                configuration JSON NOT NULL,                
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,               
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE importer.import (
                id UUID NOT NULL,
                status VARCHAR(16) NOT NULL,
                source_id UUID NOT NULL,
                transformer_id UUID NOT NULL,
                file VARCHAR(255) NOT NULL,
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                started_at TIMESTAMP WITHOUT TIME ZONE,
                ended_at TIMESTAMP WITHOUT TIME ZONE,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('
            CREATE TABLE importer.import_line (
                import_id UUID NOT NULL,
                step INT NOT NULL ,
                line INT NOT NULL ,
                created_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITHOUT TIME ZONE NOT NULL,
                processed_at TIMESTAMP WITHOUT TIME ZONE DEFAULT NULL,
                message TEXT DEFAULT NULL,                    
                PRIMARY KEY(import_id, step, line)
            )
        ');
        $this->addSql('CREATE INDEX import_line_import_id_idx ON importer.import_line USING btree (import_id)');
        $this->addSql(
            'ALTER TABLE importer.import_line ADD CONSTRAINT import_line_import_id_fk FOREIGN KEY (import_id) '.
            'REFERENCES importer.import ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->addSql('
            CREATE TABLE importer.event_store (
                id BIGSERIAL NOT NULL, 
                aggregate_id uuid NOT NULL, 
                sequence int, 
                event_id UUID NOT NULL, 
                payload jsonb NOT NULL, 
                recorded_by uuid default NULL, 
                recorded_at timestamp without time zone NOT NULL, 
                CONSTRAINT event_store_pkey PRIMARY KEY (id)
            )
        ');

        $this->addSql('
            CREATE TABLE importer.event_store_history (
                id BIGSERIAL NOT NULL, 
                aggregate_id uuid NOT NULL, 
                sequence int NOT NULL,
                variant int NOT NULL DEFAULT 1,
                event_id UUID NOT NULL, 
                payload jsonb NOT NULL, 
                recorded_by uuid default NULL, 
                recorded_at timestamp without time zone NOT NULL, 
                CONSTRAINT event_store_history_pkey PRIMARY KEY (id)
            )
        ');
        $this->addSql(
            'CREATE UNIQUE INDEX importer_event_store_history_unique_key ON importer.event_store_history '.
            ' USING btree (aggregate_id, sequence, variant)'
        );

        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'IMPORT_CREATE', 'Import']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'IMPORT_READ', 'Import']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'IMPORT_UPDATE', 'Import']
        );
        $this->addSql(
            'INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)',
            [Uuid::uuid4()->toString(), 'IMPORT_DELETE', 'Import']
        );
    }
}
