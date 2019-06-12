<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Migration\AbstractErgonodeMigration;

/**
 */
final class Version20180619083800 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS category (
                   id UUID NOT NULL,
                   name JSONB NOT NULL, 
                   code VARCHAR(255) DEFAULT NULL, 
                   sequence SERIAL, 
                   PRIMARY KEY(id)
                )'
        );
    }
}
