<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
        $this->addSql('CREATE TYPE BATCH_ACTION_STATUS AS ENUM (\'PRECESSED\', \'ENDED\', \'WAITING_FOR_DECISION\')');
        $this->addSql('ALTER TABLE batch_action ADD COLUMN auto_end_on_errors BOOLEAN DEFAULT TRUE');
        $this->addSql('ALTER TABLE batch_action ADD COLUMN status BATCH_ACTION_STATUS DEFAULT \'PRECESSED\'');

        //@todo migracja zminiajaca na ENDED
    }
}
