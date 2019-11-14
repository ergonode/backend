<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20191112075005 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Attribute']);
        $this->addSql('INSERT INTO privileges_group (area) VALUES (?)', ['Attribute group']);
    }
}
