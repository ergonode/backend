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
final class Version20211219110000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE IF NOT EXISTS audit (
                id uuid NOT NULL,
                created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                created_by UUID DEFAULT NULL,
                edited_at TIMESTAMP WITH TIME ZONE NOT NULL,
                edited_by UUID DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');

        //@todo add data migrations

        $this->addSql('ALTER TABLE product DROP column created_at');
        $this->addSql('ALTER TABLE product DROP column updated_at');
    }
}
