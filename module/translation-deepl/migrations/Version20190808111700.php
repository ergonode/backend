<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20190808111700 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS translation_cache (
                    id UUID NOT NULL, 
                    translation TEXT NOT NULL, 
                    PRIMARY KEY(id))'
        );
    }
}
