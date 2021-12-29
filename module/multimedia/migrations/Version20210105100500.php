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
final class Version20210105100500 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'MULTIMEDIA_GET_GRID',
                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_METADATA',
                'MULTIMEDIA_GET_RELATION',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
                'MULTIMEDIA_POST',
                'MULTIMEDIA_PUT',
                'MULTIMEDIA_DELETE',
            ]
        );

        //MULTIMEDIA
        $this->insertPrivileges(
            'MULTIMEDIA_READ',
            [
                'MULTIMEDIA_GET_GRID',
                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_METADATA',
                'MULTIMEDIA_GET_RELATION',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'MULTIMEDIA_CREATE',
            [
                'MULTIMEDIA_GET_GRID',
                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_METADATA',
                'MULTIMEDIA_GET_RELATION',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
                'MULTIMEDIA_POST',
                'MULTIMEDIA_PUT',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'MULTIMEDIA_UPDATE',
            [
                'MULTIMEDIA_GET_GRID',
                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_METADATA',
                'MULTIMEDIA_GET_RELATION',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
                'MULTIMEDIA_PUT',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'MULTIMEDIA_DELETE',
            [
                'MULTIMEDIA_GET_GRID',
                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_METADATA',
                'MULTIMEDIA_GET_RELATION',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
                'MULTIMEDIA_DELETE',

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
