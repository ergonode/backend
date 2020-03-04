<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

/**
 */
final class Version20180610093112 extends AbstractErgonodeMigration
{
    /**
     * @param Schema $schema
     *
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        $this->addSql(
            'CREATE TABLE language (
                      id UUID NOT NULL, 
                      iso VARCHAR(2) NOT NULL, 
                      name VARCHAR(64),
                      system BOOLEAN NOT NULL DEFAULT false, 
                      PRIMARY KEY(id)
              )'
        );
        $this->addSql(
            'CREATE TABLE translation
                    (
                        translation_id UUID NOT NULL,
                        language VARCHAR(2) NOT NULL,
                        phrase VARCHAR(255),
                        PRIMARY KEY(translation_id, language)
                    )'
        );

        foreach ($this->getLanguages() as $iso => $name) {
            $this->addSql(
                'INSERT INTO language (id, iso, name) VALUES (?, ?, ?)',
                [Uuid::uuid4()->toString(), $iso, $name]
            );
        }

        $this->addSql('UPDATE language SET system = true WHERE iso in (\'EN\', \'PL\')');

        $this->addSql('ALTER TABLE language rename column system to active');
    }

    /**
     * @return array
     */
    private function getLanguages(): array
    {
        return [
            'EN' => 'English',
            'DE' => 'German',
            'PL' => 'Polish',
            'ES' => 'Spanish',
            'FR' => 'French',
            'RU' => 'Russian',
            'UK' => 'Ukrainian',
            'TR' => 'Turkish',
            'SV' => 'Swedish',
            'PT' => 'Portuguese',
            'NL' => 'Dutch',
            'IT' => 'Italian',
            'EL' => 'Greek',
            'DA' => 'Danish',
            'CS' => 'Czech',
            'BG' => 'Bulgarian',
            'FI' => 'Finnish',
            'ZH' => 'Chinese',
            'RO' => 'Romanian',
            'HU' => 'Hungarian',
            'SR' => 'Serbian',
            'HE' => 'Hebrew',
            'HR' => 'Croatian',
            'SQ' => 'Albanian',
            'NO' => 'Norwegian',
            'SK' => 'Slovak',
            'LT' => 'Lithuanian',
            'BS' => 'Bosnian',
            'SL' => 'Slovene',
            'LV' => 'Latvian',
            'MK' => 'Macedonian',
            'ET' => 'Estonian',
            'KK' => 'Kazakh',
            'HI' => 'Hindi',
            'AR' => 'Arabic',
            'JA' => 'Japanese',
        ];
    }
}
