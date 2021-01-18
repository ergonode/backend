<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20210115150000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE exporter.export_error(
                id SERIAL, 
                export_id uuid NOT NULL,
                created_at timestamptz NOT NULL,        
                message TEXT DEFAULT NULL,  
                parameters jsonb DEFAULT NULL,
                PRIMARY KEY (id)
            )
        ');

        $this->addSql('UPDATE exporter.export_error 
                           SET (export_id, created_at, message, parameters) = 
                           (SELECT export_id, procesed_at, message, paramatars FROM exporter.export_line)
                           ');

        $this->addSql('ALTER TABLE exporter.export_line DROP COLUMN message');
        $this->addSql('ALTER TABLE exporter.export_line DROP COLUMN paramatars');
    }
}
