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
final class Version20211214160000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE status ADD COLUMN index SERIAL');

        $this->addSql('ALTER TABLE status ALTER COLUMN index DROP DEFAULT');

        $this->addSql('DROP SEQUENCE status_index_seq');
    }
}
