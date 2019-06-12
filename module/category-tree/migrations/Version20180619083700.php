<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ergonode\Migration\AbstractErgonodeMigration;

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
                    tree_id UUID NOT NULL, 
                    category_id UUID NOT NULL, 
                    path ltree, 
                    PRIMARY KEY(tree_id, category_id))'
        );
    }
}
