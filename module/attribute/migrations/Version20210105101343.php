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
final class Version20210105101343 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_DELETE',
                'ATTRIBUTE_POST',
                'ATTRIBUTE_PUT',

                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_DELETE_GROUP',
                'ATTRIBUTE_POST_GROUP',
                'ATTRIBUTE_PUT_GROUP',

                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',
                'ATTRIBUTE_GET_OPTION_COLLECTIONS',
                'ATTRIBUTE_DELETE_OPTION',
                'ATTRIBUTE_POST_OPTION',
                'ATTRIBUTE_PUT_OPTION',
            ]
        );

        //ATTRIBUTE
        $this->insertPrivileges(
            'ATTRIBUTE_READ',
            [
                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',
                'ATTRIBUTE_GET_OPTION_COLLECTIONS',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_UPDATE',
            [
                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_PUT',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',
                'ATTRIBUTE_GET_OPTION_COLLECTIONS',
                'ATTRIBUTE_PUT_OPTION',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_CREATE',
            [
                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_PUT',
                'ATTRIBUTE_POST',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',
                'ATTRIBUTE_GET_OPTION_COLLECTIONS',
                'ATTRIBUTE_PUT_OPTION',
                'ATTRIBUTE_POST_OPTION',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_DELETE',
            [
                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_DELETE_GROUP',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',
                'ATTRIBUTE_GET_OPTION_COLLECTIONS',
                'ATTRIBUTE_DELETE_OPTION',
            ]
        );

        //ATTRIBUTE_GROUP
        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_READ',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_UPDATE',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_PUT_GROUP',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_CREATE',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_POST_GROUP',
                'ATTRIBUTE_PUT_GROUP',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_DELETE',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_DELETE_GROUP',
            ]
        );
    }

    /**
     * @param string[] $privileges
     *
     * @throws \Exception
     */
    private function insertEndpointPrivileges(array $privileges): void
    {
        foreach ($privileges as $privilege) {
            $this->addSql(
                'INSERT INTO privileges (id, name) VALUES (?, ?)',
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
            'INSERT INTO privileges_group_privileges (privileges_group_id, privileges_id)
                    SELECT pg.id, p.id 
                    FROM privileges_group pg, "privileges" p 
                    WHERE pg.code = :groupName
                    AND p."name" IN(:privileges)
            ',
            [
                ':groupName' => $privilege,
                ':privileges' => $endpoints,
            ],
            [
                ':privileges' => Connection::PARAM_STR_ARRAY,
            ]
        );
    }
}
