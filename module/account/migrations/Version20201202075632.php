<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20201202075632 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE privileges_group RENAME TO privileges_family');
        $this->addSql('ALTER TABLE "privileges" RENAME TO privileges_group');
        $this->addSql('ALTER INDEX privileges_name_key RENAME TO privileges_group_code_key');

        $this->addSql(
            '
            CREATE TABLE privileges (
                id UUID NOT NULL, 
                name VARCHAR(128) NOT NULL,              
                PRIMARY KEY(id)
            )
        '
        );

        $this->addSql('CREATE UNIQUE INDEX privileges_name_key ON "privileges" (name)');

        $this->addSql(
            '
            CREATE TABLE privileges_group_privileges (
                privileges_id UUID NOT NULL,
                privileges_group_id UUID NOT NULL,                
                PRIMARY KEY(privileges_id, privileges_group_id)
            )
        '
        );

        $this->addSql(
            'ALTER TABLE privileges_group_privileges 
                    ADD CONSTRAINT privileges_group_privileges_privileges_id_fk
                        FOREIGN KEY (privileges_id) REFERENCES "privileges"(id) 
                        ON UPDATE CASCADE ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE privileges_group_privileges 
                    ADD CONSTRAINT privileges_group_privileges_group_id_fk
                        FOREIGN KEY (privileges_group_id) REFERENCES "privileges_group"(id) 
                        ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->insertPrivileges(
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',
                'ACCOUNT_DELETE_AVATAR',
                'ACCOUNT_POST',
                'ACCOUNT_PUT',
                'ACCOUNT_PUT_PASSWORD',
                'ACCOUNT_POST_AVATAR',
                'ACCOUNT_DELETE_ROLE',
                'ACCOUNT_POST_ROLE',
                'ACCOUNT_PUT_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );

        //USER
        $this->insertPrivilegesGroup(
            'USER_READ',
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',
            ]
        );

        $this->insertPrivilegesGroup(
            'USER_UPDATE',
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',
                'ACCOUNT_DELETE_AVATAR',
                'ACCOUNT_PUT',
                'ACCOUNT_PUT_PASSWORD',
                'ACCOUNT_POST_AVATAR',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );

        $this->insertPrivilegesGroup(
            'USER_CREATE',
            [
                'ACCOUNT_GET_GRID',
                'ACCOUNT_GET',
                'ACCOUNT_DELETE_AVATAR',
                'ACCOUNT_POST',
                'ACCOUNT_PUT',
                'ACCOUNT_PUT_PASSWORD',
                'ACCOUNT_POST_AVATAR',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );

        //ROLE
        $this->insertPrivilegesGroup(
            'USER_ROLE_READ',
            [
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );

        $this->insertPrivilegesGroup(
            'USER_ROLE_UPDATE',
            [
                'ACCOUNT_POST_ROLE',
                'ACCOUNT_PUT_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );

        $this->insertPrivilegesGroup(
            'USER_ROLE_CREATE',
            [
                'ACCOUNT_POST_ROLE',
                'ACCOUNT_PUT_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );

        $this->insertPrivilegesGroup(
            'USER_ROLE_DELETE',
            [
                'ACCOUNT_DELETE_ROLE',
                'ACCOUNT_GET_ROLE',
                'ACCOUNT_GET_ROLE_GRID',
            ]
        );
    }

    /**
     * @param string[] $privileges
     */
    private function insertPrivileges(array $privileges): void
    {
        foreach ($privileges as $privilege) {
            $this->addSql(
                'INSERT INTO privileges (id, name) VALUES (?, ?)',
                [Uuid::uuid4()->toString(), $privilege]
            );
        }
    }

    /**
     * @param string[] $privileges
     */
    private function insertPrivilegesGroup(string $groupName, array $privileges): void
    {
        $this->addSql(
            'INSERT INTO privileges_group_privileges (privileges_group_id, privileges_id)
                    SELECT pg.id, p.id 
                    FROM privileges_group pg, "privileges" p 
                    WHERE pg.code = :groupName
                    AND p."name" IN(:privileges)
            ',
            [
                ':groupName' => $groupName,
                ':privileges' => $privileges,
            ],
            [
                ':privileges' => \Doctrine\DBAL\Connection::PARAM_STR_ARRAY,
            ]
        );
    }
}
