<?php
/**
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
final class Version20210426110000 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'INSERT INTO privileges_endpoint (id, name) VALUES (?, ?)',
            [Uuid::uuid4()->toString(), 'PRODUCT_GET_ATTRIBUTE_RELATIONS']
        );

        $this->addPrivilege('PRODUCT_READ', 'PRODUCT_GET_ATTRIBUTE_RELATIONS');
        $this->addPrivilege('PRODUCT_UPDATE', 'PRODUCT_GET_ATTRIBUTE_RELATIONS');
    }

    private function addPrivilege(string $privilege, string $endpoint): void
    {
        $this->addSql(
            'INSERT INTO privileges_endpoint_privileges (privileges_id, privileges_endpoint_id)
                    SELECT p.id, pe.id 
                    FROM privileges_endpoint pe, "privileges" p 
                    WHERE p.code = :privilege
                    AND pe."name" = :endpoint
            ',
            [
                ':privilege' => $privilege,
                ':endpoint' => $endpoint,
            ],
        );
    }
}
