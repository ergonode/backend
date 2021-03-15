<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

final class Version20201102124000 extends AbstractErgonodeMigration
{
    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE batch_action (
                      id UUID NOT NULL, 
                      created_at TIMESTAMP WITH TIME ZONE NOT NULL,
                      type VARCHAR(20) NOT NULL, 
                      PRIMARY KEY(id)
              )'
        );

        $this->addSql(
            'CREATE TABLE batch_action_entry (
                      batch_action_id UUID NOT NULL,
                      resource_id UUID NOT NULL, 
                      success BOOLEAN DEFAULT NULL,
                      fail_reason JSONB DEFAULT NULL, 
                      processed_at TIMESTAMP WITH TIME ZONE DEFAULT NULL, 
                      PRIMARY KEY(batch_action_id, resource_id)
              )'
        );

        $this->addSql(
            'ALTER TABLE batch_action_entry
             ADD CONSTRAINT batch_action_entry_batch_action_fk FOREIGN KEY (batch_action_id) 
             REFERENCES batch_action ON UPDATE CASCADE ON DELETE CASCADE'
        );
    }
}
