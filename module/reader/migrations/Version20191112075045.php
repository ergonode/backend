<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;

/**
 */
final class Version20191112075045 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO privileges_group (area, active) VALUES (?,?)', ['Reader', 0], ['active' => \PDO::PARAM_BOOL]);
    }
}
