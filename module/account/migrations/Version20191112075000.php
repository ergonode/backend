<?php

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


        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['User']);
        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Role']);
    }
}
