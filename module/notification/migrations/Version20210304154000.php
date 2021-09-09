<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210304154000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification ADD COLUMN type varchar(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD COLUMN object_id uuid DEFAULT NULL');
    }
}
