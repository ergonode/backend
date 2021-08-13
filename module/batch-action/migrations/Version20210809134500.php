<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210809134500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE batch_action ADD COLUMN payload TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE batch_action ADD COLUMN processed_at TIMESTAMP WITH TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE batch_action ADD COLUMN created_by UUID DEFAULT NULL');
    }
}
