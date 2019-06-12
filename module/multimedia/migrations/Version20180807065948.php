<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Ergonode Migration Class:
 */
final class Version20180807065948 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE multimedia (
                id UUID NOT NULL,
                name VARCHAR(128) NOT NULL,               
                extension varchar(4) NOT NULL,
                mime VARCHAR(255) NOT NULL,
                size INTEGER NOT NULL,
                crc VARCHAR(128) NOT NULL,
                PRIMARY KEY(id)
            )
        ');
    }
}
