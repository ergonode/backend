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
final class Version20210318100000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'UPDATE "language"
                SET active = true
                WHERE active = false'
        );

        $this->addSql(
            'ALTER TABLE "language" 
                ALTER COLUMN active SET DEFAULT true'
        );
    }
}
