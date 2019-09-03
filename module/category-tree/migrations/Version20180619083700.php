<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

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
                    code VARCHAR(64) NOT NULL, 
                    name JSONB NOT NULL, 
                    PRIMARY KEY(id))'
        );

        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'CATEGORY_TREE_CREATE', 'Category tree']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'CATEGORY_TREE_READ', 'Category tree']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'CATEGORY_TREE_UPDATE', 'Category tree']);
        $this->addSql('INSERT INTO privileges (id, code, area) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), 'CATEGORY_TREE_DELETE', 'Category tree']);
    }
}
