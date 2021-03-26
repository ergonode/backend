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
final class Version20210215121148 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql('ALTER TABLE exporter.export_line RENAME TO export_line_old');

        $this->addSql('
            CREATE TABLE exporter.export_line(
                id uuid NOT NULL,
                export_id uuid NOT NULL,
                object_id uuid NOT NULL,
                processed_at timestamptz NULL,
                PRIMARY KEY (id)
            )
        ');

        $this->addSql(
            'ALTER TABLE exporter.export_line 
                    ADD CONSTRAINT export_line_export_id_fk FOREIGN KEY (export_id) 
                    REFERENCES exporter.export(id) ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->addSql(
            'INSERT INTO exporter.export_line(id, export_id, object_id, processed_at) 
                    SELECT 
                           uuid_generate_v4(),
                           export_line_old.export_id,
                           export_line_old.object_id,
                           export_line_old.processed_at
                    FROM exporter.export_line_old'
        );

        $this->addSql('DROP TABLE exporter.export_line_old');

        $this->addSql(
            'DELETE 
                    FROM exporter.export_error
                    WHERE export_error.export_id IN(
                        SELECT export_id 
                        FROM exporter.export_error 
                        LEFT JOIN exporter.export ON export.id = export_error.export_id 
                        WHERE export.id IS NULL )'
        );

        $this->addSql(
            'ALTER TABLE exporter.export_error 
                    ADD CONSTRAINT export_error_export_id_fk FOREIGN KEY (export_id) 
                    REFERENCES exporter.export(id) ON UPDATE CASCADE ON DELETE CASCADE'
        );
    }
}
