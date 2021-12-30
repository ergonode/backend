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
final class Version20210105100800 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'PRODUCT_GET_GRID',
                'PRODUCT_GET_HISTORY_GRID',
                'PRODUCT_GET_INHERITED',
                'PRODUCT_GET_TEMPLATE',
                'PRODUCT_GET',
                'PRODUCT_POST',
                'PRODUCT_PUT',
                'PRODUCT_DELETE',

                'PRODUCT_POST_ATTRIBUTE_VALIDATION',
                'PRODUCT_PUT_ATTRIBUTE',
                'PRODUCT_DELETE_ATTRIBUTE',
                'PRODUCT_DELETE_ATTRIBUTES',
                'PRODUCT_PATCH_ATTRIBUTES',

                'PRODUCT_GET_BINDING',
                'PRODUCT_POST_BINDING',
                'PRODUCT_DELETE_BINDING',

                'PRODUCT_GET_CATEGORY',
                'PRODUCT_POST_CATEGORY',
                'PRODUCT_DELETE_CATEGORY',

                'PRODUCT_GET_WIDGET',

                'PRODUCT_GET_RELATIONS_AVAILABLE',
                'PRODUCT_GET_RELATIONS_CHILDREN',
                'PRODUCT_POST_RELATIONS_CHILD_ADD',
                'PRODUCT_POST_RELATIONS_CHILDREN_SEGMENT',
                'PRODUCT_POST_RELATIONS_CHILDREN_SKU',
                'PRODUCT_DELETE_RELATIONS_CHILD',
            ]
        );

        //PRODUCT
        $this->insertPrivileges(
            'PRODUCT_READ',
            [
                'PRODUCT_GET_GRID',
                'PRODUCT_GET_HISTORY_GRID',
                'PRODUCT_GET_INHERITED',
                'PRODUCT_GET_TEMPLATE',
                'PRODUCT_GET',

                'PRODUCT_GET_BINDING',

                'PRODUCT_GET_CATEGORY',

                'PRODUCT_GET_WIDGET',

                'PRODUCT_GET_RELATIONS_AVAILABLE',
                'PRODUCT_GET_RELATIONS_CHILDREN',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'CATEGORY_GET_GRID',
                'CATEGORY_GET',

                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',

                'COMMENT_GET_GRID',
                'COMMENT_GET',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_CREATE',
            [
                'PRODUCT_GET_GRID',
                'PRODUCT_GET_HISTORY_GRID',
                'PRODUCT_GET_INHERITED',
                'PRODUCT_GET_TEMPLATE',
                'PRODUCT_GET',
                'PRODUCT_POST',
                'PRODUCT_PUT',

                'PRODUCT_POST_ATTRIBUTE_VALIDATION',
                'PRODUCT_PUT_ATTRIBUTE',
                'PRODUCT_DELETE_ATTRIBUTE',
                'PRODUCT_DELETE_ATTRIBUTES',
                'PRODUCT_PATCH_ATTRIBUTES',

                'PRODUCT_GET_BINDING',
                'PRODUCT_POST_BINDING',
                'PRODUCT_DELETE_BINDING',

                'PRODUCT_GET_CATEGORY',
                'PRODUCT_POST_CATEGORY',
                'PRODUCT_DELETE_CATEGORY',

                'PRODUCT_GET_WIDGET',

                'PRODUCT_GET_RELATIONS_AVAILABLE',
                'PRODUCT_GET_RELATIONS_CHILDREN',
                'PRODUCT_POST_RELATIONS_CHILD_ADD',
                'PRODUCT_POST_RELATIONS_CHILDREN_SEGMENT',
                'PRODUCT_POST_RELATIONS_CHILDREN_SKU',
                'PRODUCT_DELETE_RELATIONS_CHILD',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'CATEGORY_GET_GRID',
                'CATEGORY_GET',

                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',

                'COMMENT_GET_GRID',
                'COMMENT_GET',
                'COMMENT_POST',
                'COMMENT_PUT',
                'COMMENT_DELETE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_UPDATE',
            [
                'PRODUCT_GET_GRID',
                'PRODUCT_GET_HISTORY_GRID',
                'PRODUCT_GET_INHERITED',
                'PRODUCT_GET_TEMPLATE',
                'PRODUCT_GET',
                'PRODUCT_PUT',

                'PRODUCT_POST_ATTRIBUTE_VALIDATION',
                'PRODUCT_PUT_ATTRIBUTE',
                'PRODUCT_DELETE_ATTRIBUTE',
                'PRODUCT_DELETE_ATTRIBUTES',
                'PRODUCT_PATCH_ATTRIBUTES',

                'PRODUCT_GET_BINDING',
                'PRODUCT_POST_BINDING',
                'PRODUCT_DELETE_BINDING',

                'PRODUCT_GET_CATEGORY',
                'PRODUCT_POST_CATEGORY',
                'PRODUCT_DELETE_CATEGORY',

                'PRODUCT_GET_WIDGET',

                'PRODUCT_GET_RELATIONS_AVAILABLE',
                'PRODUCT_GET_RELATIONS_CHILDREN',
                'PRODUCT_POST_RELATIONS_CHILD_ADD',
                'PRODUCT_POST_RELATIONS_CHILDREN_SEGMENT',
                'PRODUCT_POST_RELATIONS_CHILDREN_SKU',
                'PRODUCT_DELETE_RELATIONS_CHILD',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'CATEGORY_GET_GRID',
                'CATEGORY_GET',

                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',

                'COMMENT_GET_GRID',
                'COMMENT_GET',
                'COMMENT_POST',
                'COMMENT_PUT',
                'COMMENT_DELETE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',

                'MULTIMEDIA_GET',
                'MULTIMEDIA_GET_DOWNLOAD',
                'MULTIMEDIA_GET_DOWNLOAD_THUMBNAIL',
            ]
        );

        $this->insertPrivileges(
            'PRODUCT_DELETE',
            [
                'PRODUCT_GET_GRID',
                'PRODUCT_GET_HISTORY_GRID',
                'PRODUCT_GET_INHERITED',
                'PRODUCT_GET_TEMPLATE',
                'PRODUCT_GET',
                'PRODUCT_DELETE',

                'PRODUCT_GET_BINDING',

                'PRODUCT_GET_CATEGORY',

                'PRODUCT_GET_WIDGET',

                'PRODUCT_GET_RELATIONS_AVAILABLE',
                'PRODUCT_GET_RELATIONS_CHILDREN',

                'ATTRIBUTE_GET_GRID',
                'ATTRIBUTE_GET',
                'ATTRIBUTE_GET_SYSTEM',
                'ATTRIBUTE_GET_GROUP_GRID',
                'ATTRIBUTE_GET_GROUP',
                'ATTRIBUTE_GET_OPTION_GRID',
                'ATTRIBUTE_GET_OPTION',

                'CATEGORY_GET_GRID',
                'CATEGORY_GET',

                'CATEGORY_GET_TREE_GRID',
                'CATEGORY_GET_TREE',

                'COMMENT_GET_GRID',
                'COMMENT_GET',
                'COMMENT_DELETE',

                'CORE_GET_LANGUAGE_GRID',
                'CORE_GET_LANGUAGE_TREE',
                'CORE_GET_UNIT_GRID',
                'CORE_GET_UNIT',

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
