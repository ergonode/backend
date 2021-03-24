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
final class Version20210105100100 extends AbstractErgonodeMigration
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
