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
final class Version20210105101000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'SEGMENT_GET_GRID',
                'SEGMENT_GET_PRODUCT_GRID',
                'SEGMENT_GET',
                'SEGMENT_POST',
                'SEGMENT_PUT',
                'SEGMENT_DELETE',
            ]
        );

        //SEGMENT
        $this->insertPrivileges(
            'SEGMENT_READ',
            [
                'SEGMENT_GET_GRID',
                'SEGMENT_GET_PRODUCT_GRID',
                'SEGMENT_GET',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'SEGMENT_CREATE',
            [
                'SEGMENT_GET_GRID',
                'SEGMENT_GET_PRODUCT_GRID',
                'SEGMENT_GET',
                'SEGMENT_POST',
                'SEGMENT_PUT',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'SEGMENT_UPDATE',
            [
                'SEGMENT_GET_GRID',
                'SEGMENT_GET_PRODUCT_GRID',
                'SEGMENT_GET',
                'SEGMENT_PUT',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'SEGMENT_DELETE',
            [
                'SEGMENT_GET_GRID',
                'SEGMENT_GET_PRODUCT_GRID',
                'SEGMENT_GET',
                'SEGMENT_DELETE',

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
