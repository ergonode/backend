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
final class Version20200429064721 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS language_tree (
                id UUID NOT NULL,
                parent_id UUID DEFAULT NULL,
                lft INT NOT NULL,
                rgt INT NOT NULL,
                code VARCHAR(5) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql(
            'INSERT INTO language_tree (id, lft, rgt, code)
                    SELECT id, 1, 4, iso FROM "language" WHERE iso=\'en\''
        );

        $this->addSql(
            'INSERT INTO language_tree (id, lft, rgt, code)
                    SELECT id, 2, 3, iso FROM "language" WHERE iso=\'pl\''
        );
    }
}
