<?php

/*
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
final class Version20210105101342 extends AbstractErgonodeMigration
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

        //CATEGORY
        $this->insertPrivileges(
            'CATEGORY_READ',
            [
                'CATEGORY_GET_GRID',
                'CATEGORY_GET',
                'CATEGORY_GET_TYPE',
                'CATEGORY_GET_TYPE_CONFIGURATION',
            ]
        );


        $this->insertPrivileges(
            'CATEGORY_CREATE',
            [
                'CATEGORY_GET_GRID',
                'CATEGORY_GET',
                'CATEGORY_POST',
                'CATEGORY_PUT',

                'CATEGORY_GET_TYPE',
                'CATEGORY_GET_TYPE_CONFIGURATION',
            ]
        );

        $this->insertPrivileges(
            'CATEGORY_UPDATE',
            [
                'CATEGORY_GET_GRID',
                'CATEGORY_GET',
                'CATEGORY_PUT',

                'CATEGORY_GET_TYPE',
                'CATEGORY_GET_TYPE_CONFIGURATION',

            ]
        );

        $this->insertPrivileges(
            'CATEGORY_DELETE',
            [
                'CATEGORY_GET_GRID',
                'CATEGORY_GET',
                'CATEGORY_DELETE',
            ]
        );

        //CATEGORY_TREE
        $this->insertPrivileges(
            'CATEGORY_TREE_READ',
            [
                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',
            ]
        );

        $this->insertPrivileges(
            'CATEGORY_TREE_CREATE',
            [
                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',
                'CATEGORY_POST_TREE',
                'CATEGORY_PUT_TREE',
            ]
        );

        $this->insertPrivileges(
            'CATEGORY_TREE_UPDATE',
            [
                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',
                'CATEGORY_PUT_TREE',
            ]
        );

        $this->insertPrivileges(
            'CATEGORY_TREE_DELETE',
            [
                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',
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
