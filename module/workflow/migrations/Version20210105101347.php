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
final class Version20210105101347 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'WORKFLOW_GET',
                'WORKFLOW_POST',
                'WORKFLOW_PUT',
                'WORKFLOW_PUT_DEFAULT_STATUS',
                'WORKFLOW_DELETE',

                'WORKFLOW_GET_PRODUCT',

                'WORKFLOW_GET_STATUS_GRID',
                'WORKFLOW_GET_STATUS',
                'WORKFLOW_POST_STATUS',
                'WORKFLOW_PUT_STATUS',
                'WORKFLOW_DELETE_STATUS',

                'WORKFLOW_GET_TRANSITION_GRID',
                'WORKFLOW_GET_TRANSITION',
                'WORKFLOW_POST_TRANSITION',
                'WORKFLOW_PUT_TRANSITION',
                'WORKFLOW_DELETE_TRANSITION',
            ]
        );

        //WORKFLOW
        $this->insertPrivileges(
            'WORKFLOW_READ',
            [
                'WORKFLOW_GET',

                'WORKFLOW_GET_PRODUCT',

                'WORKFLOW_GET_STATUS_GRID',
                'WORKFLOW_GET_STATUS',

                'WORKFLOW_GET_TRANSITION_GRID',
                'WORKFLOW_GET_TRANSITION',
            ]
        );

        $this->insertPrivileges(
            'WORKFLOW_CREATE',
            [
                'WORKFLOW_GET',
                'WORKFLOW_POST',
                'WORKFLOW_PUT',
                'WORKFLOW_PUT_DEFAULT_STATUS',

                'WORKFLOW_GET_PRODUCT',

                'WORKFLOW_GET_STATUS_GRID',
                'WORKFLOW_GET_STATUS',
                'WORKFLOW_POST_STATUS',
                'WORKFLOW_PUT_STATUS',

                'WORKFLOW_GET_TRANSITION_GRID',
                'WORKFLOW_GET_TRANSITION',
                'WORKFLOW_POST_TRANSITION',
                'WORKFLOW_PUT_TRANSITION',
            ]
        );

        $this->insertPrivileges(
            'WORKFLOW_UPDATE',
            [
                'WORKFLOW_GET',
                'WORKFLOW_PUT',
                'WORKFLOW_PUT_DEFAULT_STATUS',
                'WORKFLOW_DELETE',

                'WORKFLOW_GET_PRODUCT',

                'WORKFLOW_GET_STATUS_GRID',
                'WORKFLOW_GET_STATUS',
                'WORKFLOW_PUT_STATUS',

                'WORKFLOW_GET_TRANSITION_GRID',
                'WORKFLOW_GET_TRANSITION',
                'WORKFLOW_PUT_TRANSITION',
            ]
        );

        $this->insertPrivileges(
            'WORKFLOW_DELETE',
            [
                'WORKFLOW_GET',
                'WORKFLOW_POST',
                'WORKFLOW_PUT',
                'WORKFLOW_PUT_DEFAULT_STATUS',
                'WORKFLOW_DELETE',

                'WORKFLOW_GET_PRODUCT',

                'WORKFLOW_GET_STATUS_GRID',
                'WORKFLOW_GET_STATUS',
                'WORKFLOW_DELETE_STATUS',

                'WORKFLOW_GET_TRANSITION_GRID',
                'WORKFLOW_GET_TRANSITION',
                'WORKFLOW_DELETE_TRANSITION',
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
