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
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,               
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
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                updated_at TIMESTAMP WITH TIME ZONE NOT NULL,
                started_at TIMESTAMP WITH TIME ZONE,
                ended_at TIMESTAMP WITH TIME ZONE,
                records int NOT NULL DEFAULT 0,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql(
            'ALTER TABLE importer.import
             ADD CONSTRAINT import_source_fk FOREIGN KEY (source_id) 
             REFERENCES importer.source(id)  ON UPDATE CASCADE ON DELETE RESTRICT'
        );

        $this->addSql(
            'ALTER TABLE importer.import
             ADD CONSTRAINT import_transformer_fk FOREIGN KEY (transformer_id) 
             REFERENCES importer.transformer(id)  ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->addSql('
            CREATE TABLE importer.import_error (
                id SERIAL, 
                import_id UUID NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                message TEXT DEFAULT NULL,                    
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX import_error_import_id_idx ON importer.import_error USING btree (import_id)');
        $this->addSql(
            'ALTER TABLE importer.import_error ADD CONSTRAINT import_error_import_id_fk FOREIGN KEY (import_id) '.
            'REFERENCES importer.import ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->connection->insert('privileges_group', ['area' => 'Import']);
        $this->createImportPrivileges(
            [
                'IMPORT_CREATE',
                'IMPORT_READ',
                'IMPORT_UPDATE',
                'IMPORT_DELETE',
            ]
        );
    }

    /**
     * @param array $collection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function createImportPrivileges(array $collection): void
    {
        foreach ($collection as $code) {
            $this->connection->insert('privileges', [
                'id' => Uuid::uuid4()->toString(),
                'code' => $code,
                'area' => 'Import',
            ]);
        }
    }
}
