<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20201027125000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE users ALTER COLUMN password TYPE VARCHAR(128)');
        $this->addSql('ALTER TABLE users ALTER COLUMN username TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ADD COLUMN avatar BOOLEAN NOT NULL DEFAULT false');
        $this->addSql('UPDATE users SET avatar = true WHERE avatar_filename <> null');
        $this->addSql('ALTER TABLE users DROP COLUMN avatar_filename');
    }
}
