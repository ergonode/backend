<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210105100200 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'CATEGORY_GET_GRID',
                'CATEGORY_GET',
                'CATEGORY_POST',
                'CATEGORY_PUT',
                'CATEGORY_DELETE',

                'CATEGORY_GET_TYPE',
                'CATEGORY_GET_TYPE_CONFIGURATION',

                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',
                'CATEGORY_POST_TREE',
                'CATEGORY_PUT_TREE',
                'CATEGORY_DELETE_TREE',
            ]
        );
    }

    /**
     * @throws \Exception
     *
     * @param string[] $privileges
     *
     */
    private function insertEndpointPrivileges(array $privileges): void
    {
        foreach ($privileges as $privilege) {
            $this->addSql(
                'INSERT INTO privileges_endpoint (id, name) VALUES (?, ?)',
                [Uuid::uuid4()->toString(), $privilege]
            );
        }
    }
}
