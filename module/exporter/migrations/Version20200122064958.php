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
* Auto-generated Ergonode Migration Class:
*/
final class Version20200122064958 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS exporter');
        $this->addSql(
            'CREATE TABLE exporter.product(
                    id uuid NOT NULL,
                    data jsonb NOT NULL,
                    type VARCHAR(255) NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE exporter.category(
                    id uuid NOT NULL,
                    code varchar(255) NULL DEFAULT NULL::character varying,
                    data jsonb NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE exporter.tree(
                    id uuid NOT NULL,
                    data jsonb NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql(
            'CREATE TABLE exporter.attribute(
                    id uuid NOT NULL,
                    code varchar(255) NULL DEFAULT NULL::character varying,
                    data jsonb NOT NULL,
                    PRIMARY KEY (id)
                 )'
        );

        $this->addSql('
            CREATE TABLE exporter.export(
                id uuid NOT NULL,
                status VARCHAR(16) NOT NULL,
                channel_id uuid NOT NULL,
                items int NOT NULL,
                created_at timestamp NOT NULL,
                updated_at timestamp NOT NULL,
                started_at timestamp NULL,
                ended_at timestamp NULL,
                PRIMARY KEY (id)
            )
        ');
        $this->addSql(
            'ALTER TABLE exporter.export 
                    ADD CONSTRAINT export_channel_id_fk FOREIGN KEY (channel_id) 
                    REFERENCES exporter.channel(id) ON UPDATE CASCADE ON DELETE RESTRICT'
        );

        $this->addSql('
            CREATE TABLE exporter.export_line(
                export_id uuid NOT NULL,
                object_id uuid NOT NULL,
                processed_at timestamp NOT NULL,        
                message TEXT DEFAULT NULL,  
                PRIMARY KEY (export_id, object_id)
            )
        ');

        $this->addSql(
            'ALTER TABLE exporter.export_line 
                    ADD CONSTRAINT export_line_export_id_fk FOREIGN KEY (export_id) 
                    REFERENCES exporter.export(id) ON UPDATE CASCADE ON DELETE CASCADE'
        );
    }
}
