<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

class Version20210615120000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'UPDATE privileges_endpoint
                    SET name = CONCAT(\'ERGONODE_ROLE_\',name) 
                    WHERE LEFT(name,14) != \'ERGONODE_ROLE_\'
                    '
        );
    }
}
