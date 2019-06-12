<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Migration\AbstractErgonodeMigration;

/**
 * Auto-generated Ergonode Migration Class
 */
final class Version20180619100000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA IF NOT EXISTS importer');

        $this->addSql(
            'CREATE TABLE IF NOT EXISTS  importer.reader (
                    id UUID NOT NULL,
                    name VARCHAR(64) NOT NULL,
                    type VARCHAR(32) NOT NULL,
                    PRIMARY KEY(id)
             )'
        );
    }
}
