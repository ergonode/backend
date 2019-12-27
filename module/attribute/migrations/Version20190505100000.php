<?php

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20190505100000 extends AbstractErgonodeMigration
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

        $this->addSql('CREATE TABLE unit (id UUID NOT NULL, name VARCHAR(64), unit VARCHAR(8) NOT NULL, PRIMARY KEY(id))');

        foreach ($this->getUnits() as $name => $unit) {
            $this->addSql('INSERT INTO unit (id, name, unit) VALUES (?, ?, ?)', [Uuid::uuid4()->toString(), $name, $unit]);
        }
    }

    /**
     * @return array
     */
    private function getUnits(): array
    {
        return [
            'metre' => 'm',
            'kilogram' => 'Kg',
            'second' => 's',
            'ampere' => 'A',
            'kelvin' => 'K',
            'mole' => 'mol',
            'candela' => 'cd',
            'radian' => 'rad',
            'steradian' => 'sr',
            'hertz' => 'Hz',
            'newton' => 'N',
            'pascal' => 'Pa',
            'joule' => 'J',
            'watt' => 'W',
            'coulomb' => 'C',
            'volt' => 'V',
            'farad' => 'F',
            'ohm' => 'Ω',
            'siemens' => 'S',
            'weber' => 'Wb',
            'tesla' => 'T',
            'henry' => 'H',
            'degree Celsius' => '°C',
            'lumen' => 'lm',
            'lux' => 'lx',
            'becquerel' => 'Bq',
            'gray' => 'Gy',
            'sievert' => 'Sv',
            'katal' => 'kat',
        ];
    }
}
