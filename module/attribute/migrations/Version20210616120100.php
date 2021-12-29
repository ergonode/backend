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
final class Version20210616120100 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivilege('ERGONODE_ROLE_ATTRIBUTE_POST_VALIDATION');

        $this->insertPrivileges(
            'ATTRIBUTE_UPDATE',
            [
                'ERGONODE_ROLE_ATTRIBUTE_POST_VALIDATION',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_CREATE',
            [
                'ERGONODE_ROLE_ATTRIBUTE_POST_VALIDATION',
            ]
        );
    }

    private function insertEndpointPrivilege(string $privilege): void
    {
        $this->addSql(
            'INSERT INTO privileges_endpoint (id, name) VALUES (?, ?)',
            [Uuid::uuid4()->toString(), $privilege]
        );
    }

    /**
     * @param string[] $endpoints
     */
    private function insertPrivileges(string $privilege, array $endpoints): void
    {
        $this->addSql(
            'INSERT INTO privileges_endpoint_privileges (privileges_id, privileges_endpoint_id)
                    SELECT p.id, pe.id 
                    FROM privileges_endpoint pe, "privileges" p 
                    WHERE p.code = :privilege
                    AND pe."name" IN(:endpoints)
            ',
            [
                ':privilege' => $privilege,
                ':endpoints' => $endpoints,
            ],
            [
                ':endpoints' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }
}
