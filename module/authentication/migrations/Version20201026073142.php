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
final class Version20201026073142 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE refresh_tokens (
                id bigserial NOT NULL, 
                refresh_token VARCHAR(128) NOT NULL, 
                username VARCHAR(255) NOT NULL, 
                valid timestamp with time zone NOT NULL,       
                PRIMARY KEY(id)
            )
        ');

        $this->addSql(
            'CREATE UNIQUE INDEX refresh_tokens_unique_key ON refresh_tokens USING btree (refresh_token)'
        );
    }
}
