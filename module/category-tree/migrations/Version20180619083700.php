<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20180619083700 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS tree (
                    id UUID NOT NULL, 
                    name VARCHAR(64) NOT NULL, 
                    PRIMARY KEY(id))'
        );
    }
}
