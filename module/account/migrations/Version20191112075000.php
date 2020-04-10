<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20191112075000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE privileges_group (
                area VARCHAR(128) NOT NULL,
                description TEXT DEFAULT NULL,    
                active BOOL NOT NULL DEFAULT true,          
                PRIMARY KEY(area)
            )
        ');

        $this->addSql('
            CREATE TABLE language_privileges (
                id UUID NOT NULL, 
                language VARCHAR(128) NOT NULL,              
                code VARCHAR(128) NOT NULL,
                PRIMARY KEY(id)
            )
        ');

        $this->addSql('ALTER TABLE users ADD language_privileges json DEFAULT NULL');

        $this->addSql('CREATE UNIQUE INDEX language_privileges_name_key ON privileges (code)');

        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['User']);
        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Role']);
    }
}
