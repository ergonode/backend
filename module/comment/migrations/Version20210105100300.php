<?php
/*
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
final class Version20210105100300 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->insertEndpointPrivileges(
            [
                'COMMENT_GET_GRID',
                'COMMENT_GET',
                'COMMENT_POST',
                'COMMENT_PUT',
                'COMMENT_DELETE',
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
}
