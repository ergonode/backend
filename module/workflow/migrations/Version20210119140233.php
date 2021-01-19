<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20210119140233 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            'ALTER TABLE product_workflow_status
                    DROP CONSTRAINT product_workflow_status_pkey'
        );

        $this->addSql(
            'ALTER TABLE product_workflow_status
                    ADD CONSTRAINT product_workflow_status_pkey PRIMARY KEY (product_id, "language")'
        );
    }
}
