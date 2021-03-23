<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

class Version20210309120000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE roles ALTER COLUMN privileges TYPE jsonb');
        $this->addSql('ALTER TABLE users ALTER COLUMN language_privileges_collection TYPE jsonb');
    }
}
