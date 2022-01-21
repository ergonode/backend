<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20211216160000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'INSERT INTO privileges_endpoint (id, name) VALUES (?, ?)',
            [Uuid::uuid4()->toString(), 'ERGONODE_ROLE_WORKFLOW_POST_STATUS_ORDER']
        );

        $this->addSql(
            'INSERT INTO privileges_endpoint_privileges (privileges_id, privileges_endpoint_id)
                    SELECT p.id, pe.id 
                    FROM privileges_endpoint pe, "privileges" p 
                    WHERE p.code = :privilege
                    AND pe."name" IN(:endpoints)
            ',
            [
                ':privilege' => 'WORKFLOW_CREATE',
                ':endpoints' => ['ERGONODE_ROLE_WORKFLOW_POST_STATUS_ORDER', ],
            ],
            [
                ':endpoints' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }
}
