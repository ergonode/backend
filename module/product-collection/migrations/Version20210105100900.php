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
final class Version20210105100900 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',
                'PRODUCT_COLLECTION_POST',
                'PRODUCT_COLLECTION_PUT',
                'PRODUCT_COLLECTION_DELETE',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SEGMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SKU',
                'PRODUCT_COLLECTION_POST_ELEMENT',
                'PRODUCT_COLLECTION_PUT_ELEMENT',
                'PRODUCT_COLLECTION_DELETE_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',
                'PRODUCT_COLLECTION_POST_TYPE',
                'PRODUCT_COLLECTION_PUT_TYPE',
                'PRODUCT_COLLECTION_DELETE_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',
            ]
        );

        //PRODUCT_COLLECTION
        $this->insertPrivileges(
            'PRODUCT_COLLECTION_READ',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_COLLECTION_CREATE',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',
                'PRODUCT_COLLECTION_POST',
                'PRODUCT_COLLECTION_PUT',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SEGMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SKU',
                'PRODUCT_COLLECTION_POST_ELEMENT',
                'PRODUCT_COLLECTION_PUT_ELEMENT',
                'PRODUCT_COLLECTION_DELETE_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',
                'PRODUCT_COLLECTION_POST_TYPE',
                'PRODUCT_COLLECTION_PUT_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_COLLECTION_UPDATE',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',
                'PRODUCT_COLLECTION_PUT',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SEGMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SKU',
                'PRODUCT_COLLECTION_POST_ELEMENT',
                'PRODUCT_COLLECTION_PUT_ELEMENT',
                'PRODUCT_COLLECTION_DELETE_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',
                'PRODUCT_COLLECTION_PUT_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_COLLECTION_DELETE',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',
                'PRODUCT_COLLECTION_DELETE',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SEGMENT',
                'PRODUCT_COLLECTION_POST_ELEMENT_SKU',
                'PRODUCT_COLLECTION_POST_ELEMENT',
                'PRODUCT_COLLECTION_PUT_ELEMENT',
                'PRODUCT_COLLECTION_DELETE_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',
                'PRODUCT_COLLECTION_DELETE_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
            ]
        );

        // PRODUCT
        $this->insertPrivileges(
            'PRODUCT_READ',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_CREATE',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_UPDATE',
            [
                'PRODUCT_COLLECTION_GET_GRID',
                'PRODUCT_COLLECTION_GET',

                'PRODUCT_COLLECTION_GET_ELEMENT_GRID',
                'PRODUCT_COLLECTION_GET_ELEMENT',

                'PRODUCT_COLLECTION_GET_TYPE_GRID',
                'PRODUCT_COLLECTION_GET_TYPE',

                'PRODUCT_COLLECTION_GET_PRODUCT_GRID',
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
