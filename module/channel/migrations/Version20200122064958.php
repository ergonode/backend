<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20200122064958 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS exporter');

        $this->addSql('
            CREATE TABLE exporter.export(
                id uuid NOT NULL,
                status VARCHAR(16) NOT NULL,
                channel_id uuid NOT NULL,
                items int NOT NULL,
                created_at timestamptz NOT NULL,
                updated_at timestamptz NOT NULL,
                started_at timestamptz NULL,
                ended_at timestamptz NULL,
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
                processed_at timestamptz NOT NULL,        
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
