<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210105100000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER INDEX privileges_name_key RENAME TO privileges_code_key');

        $this->addSql(
            '
            CREATE TABLE privileges_endpoint (
                id UUID NOT NULL, 
                name VARCHAR(128) NOT NULL,              
                PRIMARY KEY(id)
            )
        '
        );

        $this->addSql('CREATE UNIQUE INDEX privileges_endpoint_name_key ON "privileges_endpoint" (name)');

        $this->addSql(
            '
            CREATE TABLE privileges_endpoint_privileges (
                privileges_id UUID NOT NULL,
                privileges_endpoint_id UUID NOT NULL,                
                PRIMARY KEY(privileges_id, privileges_endpoint_id)
            )
        '
        );

        $this->addSql(
            'ALTER TABLE privileges_endpoint_privileges 
                    ADD CONSTRAINT privileges_endpoint_privileges_privileges_id_fk
                        FOREIGN KEY (privileges_id) REFERENCES "privileges"(id) 
                        ON UPDATE CASCADE ON DELETE CASCADE'
        );
        $this->addSql(
            'ALTER TABLE privileges_endpoint_privileges 
                    ADD CONSTRAINT privileges_endpoint_privileges_privileges_endpoint_id_fk
                        FOREIGN KEY (privileges_endpoint_id) REFERENCES "privileges_endpoint"(id) 
                        ON UPDATE CASCADE ON DELETE CASCADE'
        );

        $this->insertEndpointPrivileges(
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
                'INSERT INTO privileges_endpoint (id, name) VALUES (?, ?)',
                [Uuid::uuid4()->toString(), $privilege]
            );
        }
    }
}
