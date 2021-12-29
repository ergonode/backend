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
final class Version20210105101200 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'CHANNEL_GET_GRID',
                'CHANNEL_GET_CONFIGURATION_GRID',
                'CHANNEL_GET',
                'CHANNEL_POST',
                'CHANNEL_PUT',
                'CHANNEL_DELETE',

                'CHANNEL_GET_EXPORT_GRID',
                'CHANNEL_GET_EXPORT_ERROR_GRID',
                'CHANNEL_GET_EXPORT_FILE',
                'CHANNEL_GET_EXPORT',
                'CHANNEL_POST_EXPORT',

                'CHANNEL_GET_NOTIFICATION',

                'CHANNEL_GET_SCHEDULER',
                'CHANNEL_PUT_SCHEDULER',
            ]
        );

        //CHANNEL
        $this->insertPrivileges(
            'CHANNEL_READ',
            [
                'CHANNEL_GET_GRID',
                'CHANNEL_GET_CONFIGURATION_GRID',
                'CHANNEL_GET',

                'CHANNEL_GET_EXPORT_GRID',
                'CHANNEL_GET_EXPORT_ERROR_GRID',
                'CHANNEL_GET_EXPORT_FILE',
                'CHANNEL_GET_EXPORT',

                'CHANNEL_GET_NOTIFICATION',

                'CHANNEL_GET_SCHEDULER',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'CHANNEL_CREATE',
            [
                'CHANNEL_GET_GRID',
                'CHANNEL_GET_CONFIGURATION_GRID',
                'CHANNEL_GET',
                'CHANNEL_POST',
                'CHANNEL_PUT',

                'CHANNEL_GET_EXPORT_GRID',
                'CHANNEL_GET_EXPORT_ERROR_GRID',
                'CHANNEL_GET_EXPORT_FILE',
                'CHANNEL_GET_EXPORT',
                'CHANNEL_POST_EXPORT',

                'CHANNEL_GET_NOTIFICATION',

                'CHANNEL_GET_SCHEDULER',
                'CHANNEL_PUT_SCHEDULER',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'CHANNEL_UPDATE',
            [
                'CHANNEL_GET_GRID',
                'CHANNEL_GET_CONFIGURATION_GRID',
                'CHANNEL_GET',
                'CHANNEL_PUT',

                'CHANNEL_GET_EXPORT_GRID',
                'CHANNEL_GET_EXPORT_ERROR_GRID',
                'CHANNEL_GET_EXPORT_FILE',
                'CHANNEL_GET_EXPORT',
                'CHANNEL_POST_EXPORT',

                'CHANNEL_GET_NOTIFICATION',

                'CHANNEL_GET_SCHEDULER',
                'CHANNEL_PUT_SCHEDULER',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'CHANNEL_DELETE',
            [
                'CHANNEL_GET_GRID',
                'CHANNEL_GET_CONFIGURATION_GRID',
                'CHANNEL_GET',
                'CHANNEL_DELETE',

                'CHANNEL_GET_EXPORT_GRID',
                'CHANNEL_GET_EXPORT_ERROR_GRID',
                'CHANNEL_GET_EXPORT_FILE',
                'CHANNEL_GET_EXPORT',

                'CHANNEL_GET_NOTIFICATION',

                'CHANNEL_GET_SCHEDULER',

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
