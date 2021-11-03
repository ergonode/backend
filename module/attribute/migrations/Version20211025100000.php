<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20211025100000 extends AbstractErgonodeMigration
{
    private const CURRENCIES = [
        'BHD' => 'Dinar',
        'BRL' => 'Real',
        'HRK' => 'Croatian kuna',
        'CZK' => 'Czech koruna',
        'DKK' => 'Danish krone',
        'JPY' => 'Japanese yen',
        'KHR' => 'Riel',
        'CAD' => 'Canadian dollar',
        'QAR' => 'Rial',
        'KZT' => 'Tenge',
        'KES' => 'Shilling',
        'MKD' => 'Denar',
        'MXN' => 'Pesos',
        'NOK' => 'Norwegian krone',
        'NZD' => 'New Zealand dollar',
        'RON' => 'Romanian leu',
        'SEK' => 'Swedish krona',
        'TRY' => 'Turkish lira',
        'HUF' => 'Hungarian forint',
        'AED' => 'Dirham',
        'UAH' => 'Ukrainian hryvnia',
        'KRW' => 'Won',
    ];

    /**
     * @throws \Exception
     */
    public function up(Schema $schema): void
    {
        foreach (self::CURRENCIES as $iso => $name) {

            $currency = $this->connection->executeQuery('SELECT id FROM currency WHERE iso = :id', ['id' => $iso])
                ->fetchOne();
            if (!$currency) {
                $this->addSql(
                    'INSERT INTO currency (id, iso, name) VALUES (?, ?, ?)',
                    [Uuid::uuid4()->toString(), $iso, $name]
                );
            }
        }

        $this->addSql(
            'UPDATE currency SET name = \'Chinese yuan\' WHERE name = \'Chinese Yuan Renminbi\' AND iso = \'CNY\''
        );

        $this->addSql(
            'UPDATE currency SET name = \'Pound sterling\' WHERE name = \'Pound Sterling\' AND iso = \'GBP\''
        );

        $this->addSql(
            'UPDATE currency SET name = \'US dollar\' WHERE name = \'US Dollar\' AND iso = \'USD\''
        );

        $this->addSql(
            'UPDATE currency SET name = \'Russian ruble\' WHERE name = \'Russian Ruble\' AND iso = \'RUB\''
        );
    }
}
