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
final class Version20210217083000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql('ALTER TABLE importer.import_line ADD COLUMN status VARCHAR(16) DEFAULT NULL');
        $this->addSql('UPDATE importer.import_line SET status = \'success\'');
        $this->addSql('ALTER TABLE importer.import_line ALTER COLUMN status DROP DEFAULT');
        $this->addSql('ALTER TABLE importer.import_line ADD COLUMN id UUID DEFAULT NULL');
        $this->addSql('UPDATE importer.import_line SET id = uuid_generate_v4()');
        $this->addSql('ALTER TABLE importer.import_line ALTER COLUMN id DROP DEFAULT');
        $this->addSql('ALTER TABLE importer.import_line ALTER COLUMN id SET NOT NULL');
        $this->addSql('ALTER TABLE importer.import_line DROP CONSTRAINT import_line_pkey');
        $this->addSql('ALTER TABLE importer.import_line ADD CONSTRAINT import_line_pkey PRIMARY KEY(id)');
        $this->addSql('ALTER TABLE importer.import_line ALTER COLUMN object_id DROP NOT NULL');
        $this->addSql('ALTER TABLE importer.import_line ALTER COLUMN object_id DROP NOT NULL');
        $this->addSql('ALTER TABLE importer.import_line ALTER COLUMN processed_at DROP NOT NULL');
        $this->addSql('ALTER TABLE importer.import DROP COLUMN records');
    }
}
