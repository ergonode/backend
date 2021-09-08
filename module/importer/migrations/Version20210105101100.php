<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
final class Version20210105101100 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'IMPORT_GET_GRID',
                'IMPORT_GET_GRID_ERROR',
                'IMPORT_GET',
                'IMPORT_POST',
                'IMPORT_DELETE',

                'IMPORT_GET_SOURCE_GRID',
                'IMPORT_GET_SOURCE_CONFIGURATION_GRID',
                'IMPORT_GET_SOURCE',
                'IMPORT_POST_SOURCE',
                'IMPORT_PUT_SOURCE',
                'IMPORT_DELETE_SOURCE',

                'IMPORT_GET_NOTIFICATION',
            ]
        );

        //IMPORT
        $this->insertPrivileges(
            'IMPORT_READ',
            [
                'IMPORT_GET_GRID',
                'IMPORT_GET_GRID_ERROR',
                'IMPORT_GET',

                'IMPORT_GET_SOURCE_GRID',
                'IMPORT_GET_SOURCE_CONFIGURATION_GRID',
                'IMPORT_GET_SOURCE',

                'IMPORT_GET_NOTIFICATION',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'IMPORT_CREATE',
            [
                'IMPORT_GET_GRID',
                'IMPORT_GET_GRID_ERROR',
                'IMPORT_GET',
                'IMPORT_POST',
                'IMPORT_DELETE',

                'IMPORT_GET_SOURCE_GRID',
                'IMPORT_GET_SOURCE_CONFIGURATION_GRID',
                'IMPORT_GET_SOURCE',
                'IMPORT_POST_SOURCE',
                'IMPORT_PUT_SOURCE',

                'IMPORT_GET_NOTIFICATION',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'IMPORT_UPDATE',
            [
                'IMPORT_GET_GRID',
                'IMPORT_GET_GRID_ERROR',
                'IMPORT_GET',
                'IMPORT_POST',
                'IMPORT_DELETE',

                'IMPORT_GET_SOURCE_GRID',
                'IMPORT_GET_SOURCE_CONFIGURATION_GRID',
                'IMPORT_GET_SOURCE',
                'IMPORT_PUT_SOURCE',

                'IMPORT_GET_NOTIFICATION',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'IMPORT_DELETE',
            [
                'IMPORT_GET_GRID',
                'IMPORT_GET_GRID_ERROR',
                'IMPORT_GET',
                'IMPORT_DELETE',

                'IMPORT_GET_SOURCE_GRID',
                'IMPORT_GET_SOURCE_CONFIGURATION_GRID',
                'IMPORT_GET_SOURCE',
                'IMPORT_DELETE_SOURCE',

                'IMPORT_GET_NOTIFICATION',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
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
