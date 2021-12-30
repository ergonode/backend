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
final class Version20210105100600 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'DESIGNER_GET_TEMPLATE_GRID',
                'DESIGNER_GET_TEMPLATE',
                'DESIGNER_POST_TEMPLATE',
                'DESIGNER_PUT_TEMPLATE',
                'DESIGNER_DELETE_TEMPLATE',
            ]
        );

        //TEMPLATE_DESIGNER
        $this->insertPrivileges(
            'TEMPLATE_DESIGNER_READ',
            [
                'DESIGNER_GET_TEMPLATE_GRID',
                'DESIGNER_GET_TEMPLATE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
            ]
        );

        $this->insertPrivileges(
            'TEMPLATE_DESIGNER_CREATE',
            [
                'DESIGNER_GET_TEMPLATE_GRID',
                'DESIGNER_GET_TEMPLATE',
                'DESIGNER_POST_TEMPLATE',
                'DESIGNER_PUT_TEMPLATE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
            ]
        );

        $this->insertPrivileges(
            'TEMPLATE_DESIGNER_UPDATE',
            [
                'DESIGNER_GET_TEMPLATE_GRID',
                'DESIGNER_GET_TEMPLATE',
                'DESIGNER_PUT_TEMPLATE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
            ]
        );

        $this->insertPrivileges(
            'TEMPLATE_DESIGNER_DELETE',
            [
                'DESIGNER_GET_TEMPLATE_GRID',
                'DESIGNER_GET_TEMPLATE',
                'DESIGNER_DELETE_TEMPLATE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
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
