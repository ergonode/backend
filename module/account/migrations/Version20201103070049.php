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
final class Version20201103070049 extends AbstractErgonodeMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users_token (
                user_id UUID NOT NULL, 
                token VARCHAR(255) NOT NULL,
                expires_at timestamp with time zone NOT NULL,
                consumed timestamp with time zone DEFAULT NULL, 
                PRIMARY KEY(token)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX users_token_token_key ON users_token ("token")');
        $this->addSql(
            'ALTER TABLE users_token
                    ADD CONSTRAINT users_token_users_fk FOREIGN KEY (user_id) 
                    REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE'
        );
    }
}
