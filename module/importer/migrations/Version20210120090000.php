<?php
/*
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
* Auto-generated Ergonode Migration Class:
*/
final class Version20210120090000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE importer.import_line(
                import_id uuid NOT NULL,
                object_id uuid NOT NULL,
                type VARCHAR(64) NOT NULL,
                processed_at timestamptz NOT NULL,             
                PRIMARY KEY (import_id, object_id)
            )
        ');
    }
}
