<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210105120000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        //USER
        $this->insertPrivileges(
            'USER_READ',
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'USER_UPDATE',
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',
                'ACCOUNT_DELETE_AVATAR',
                'ACCOUNT_PUT',
                'ACCOUNT_PUT_PASSWORD',
                'ACCOUNT_POST_AVATAR',
                'ACCOUNT_GET_ROLE_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'USER_CREATE',
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',
                'ACCOUNT_DELETE_AVATAR',
                'ACCOUNT_POST',
                'ACCOUNT_PUT',
                'ACCOUNT_PUT_PASSWORD',
                'ACCOUNT_POST_AVATAR',
                'ACCOUNT_GET_ROLE_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        //ROLE
        $this->insertPrivileges(
            'USER_ROLE_READ',
            [
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'USER_ROLE_UPDATE',
            [
                'ACCOUNT_POST_ROLE',
                'ACCOUNT_PUT_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'USER_ROLE_CREATE',
            [
                'ACCOUNT_POST_ROLE',
                'ACCOUNT_PUT_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'USER_ROLE_DELETE',
            [
                'ACCOUNT_DELETE_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
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
