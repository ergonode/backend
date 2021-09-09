<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20201027131500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE importer.import DROP CONSTRAINT import_transformer_fk');
        $this->addSql('ALTER TABLE importer.import DROP COLUMN transformer_id');
        $this->addSql('ALTER TABLE importer.import_error ADD COLUMN parameters jsonb DEFAULT NULL');
    }
}
