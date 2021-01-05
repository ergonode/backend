<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
class Version20210104111110 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE importer.import
            DROP CONSTRAINT import_source_fk,
             ADD CONSTRAINT import_source_fk FOREIGN KEY (source_id) 
             REFERENCES importer.source(id)  ON UPDATE CASCADE ON DELETE CASCADE'
        );
    }
}
