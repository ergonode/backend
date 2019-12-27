<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20190505110000 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "ltree"');

        $this->addSql('CREATE TABLE currency (id UUID NOT NULL, iso VARCHAR(3) NOT NULL, name VARCHAR(64), PRIMARY KEY(id))');

        foreach ($this->getCurrencies() as $iso => $name) {
            $this->addSql('INSERT INTO currency (id, iso, name) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), $iso, $name]);
        }
    }

    /**
     * @return array
     */
    private function getCurrencies(): array
    {
        return [
            'GBP' => 'Pound Sterling',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'PLN' => 'Zloty',
            'RUB' => 'Russian Ruble',
            'JPY' => 'Japanese yen',
            'AUD' => 'Australian dollar',
            'CAD' => 'Canadian dollar',
            'CHF' => 'Swiss franc',
            'CNY' => 'Chinese Yuan Renminbi',
        ];
    }
}
