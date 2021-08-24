<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20210823140000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE designer.template ADD COLUMN code VARCHAR(128) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX template_code_unique_key ON designer.template USING btree (code)');
    }
}
