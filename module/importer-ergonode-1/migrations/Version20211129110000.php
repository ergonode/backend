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
final class Version20211129110000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('UPDATE importer.source 
            SET configuration =  configuration::jsonb || \'{"headers" : []}\'::jsonb WHERE type = \'ergonode-zip\'', );

    }
}