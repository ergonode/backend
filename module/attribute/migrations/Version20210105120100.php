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
final class Version20210105120100 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
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

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
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

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
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

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_DELETE',
            [
                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_DELETE',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',
                'ATTRIBUTE_GET_OPTION_COLLECTIONS',
                'ATTRIBUTE_DELETE_OPTION',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
            ]
        );

        //ATTRIBUTE_GROUP
        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_READ',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_UPDATE',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_PUT_GROUP',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_CREATE',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_POST_GROUP',
                'ATTRIBUTE_PUT_GROUP',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'ATTRIBUTE_GROUP_DELETE',
            [
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_DELETE_GROUP',

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
