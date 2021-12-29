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
final class Version20210105100400 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE',
                'CORE_PUT_LANGUAGE',

                'CORE_GET_LANGUAGE_TREE',
                'CORE_PUT_LANGUAGE_TREE',

                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',
                'CORE_POST_UNIT',
                'CORE_PUT_UNIT',
                'CORE_DELETE_UNIT',
            ]
        );

        //SETTINGS
        $this->insertPrivileges(
            'SETTINGS_READ',
            [
                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE',

                'CORE_GET_LANGUAGE_TREE',

                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',
            ]
        );

        $this->insertPrivileges(
            'SETTINGS_CREATE',
            [
                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE',
                'CORE_PUT_LANGUAGE',

                'CORE_GET_LANGUAGE_TREE',
                'CORE_PUT_LANGUAGE_TREE',

                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',
                'CORE_POST_UNIT',
                'CORE_PUT_UNIT',
            ]
        );

        $this->insertPrivileges(
            'SETTINGS_UPDATE',
            [
                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE',
                'CORE_PUT_LANGUAGE',

                'CORE_GET_LANGUAGE_TREE',
                'CORE_PUT_LANGUAGE_TREE',

                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',
                'CORE_PUT_UNIT',
            ]
        );

        $this->insertPrivileges(
            'SETTINGS_DELETE',
            [
                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE',

                'CORE_GET_LANGUAGE_TREE',

                'CORE_GET_UNIT_GRID',
                'CORE_DELETE_UNIT',
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
