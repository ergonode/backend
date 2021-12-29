<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210909142000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE batch_action ADD COLUMN auto_end_on_errors BOOLEAN DEFAULT TRUE');
        $this->addSql('ALTER TABLE batch_action ADD COLUMN status VARCHAR(24) DEFAULT \'PRECESSED\'');
        $this->addSql('UPDATE batch_action SET status = \'ENDED\' WHERE processed_at IS NOT NULL');
    }
}
