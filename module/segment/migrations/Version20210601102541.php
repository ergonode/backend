<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210601102541 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
            $this->addSql('DELETE FROM condition_set WHERE id IN (
                        SELECT condition_set.id
                        FROM condition_set
                        LEFT JOIN workflow_transition ON workflow_transition.condition_set_id = condition_set.id 
                        LEFT JOIN segment ON segment.condition_set_id = condition_set.id 
                        WHERE 
                              workflow_transition.condition_set_id IS NULL
                          AND segment.condition_set_id IS NULL
                        )
                        ');
    }
}
