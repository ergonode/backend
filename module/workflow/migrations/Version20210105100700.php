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
final class Version20210105100700 extends AbstractErgonodeMigration
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
