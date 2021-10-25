<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Migration;

use Doctrine\DBAL\Schema\Schema;
use Ramsey\Uuid\Uuid;

final class Version20211025100000 extends AbstractErgonodeMigration
{
    private const CURRENCIES = [
        'AUD' => 'Australian Dollar',
        'BHD' => 'Dinar',
        'BRL' => 'Real',
        'CNY' => 'Chinese Yuan',
        'HRK' => 'Croatian Kuna',
        'CZK' => 'Czech koruna',
        'DKK' => 'Danish krone',
        'JPY' => 'Japanese yen',
        'KHR' => 'Riel',
        'CAD' => 'Canadian dollar',
        'QAR' => 'Rial',
        'KZT' => 'Tenge',
        'KES' => 'Shilling',
        'CHF' => 'Swiss Franc',
        'MKD' => 'Denar',
        'MXN' => 'Pesos',
        'NOK' => 'Norwegian krone',
        'NZD' => 'New Zealand dollar',
        'RUB' => 'Russian ruble',
        'RON' => 'Romanian Leu',
        'SEK' => 'Swedish krona',
        'TRY' => 'Turkish Lira',
        'HUF' => 'Hungarian Forint',
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
            $this->addSql(
                'INSERT INTO currency (id, iso, name) VALUES (?, ?, ?) ON CONFLICT DO NOTHING',
                [Uuid::uuid4()->toString(), $iso, $name]
            );
        }
    }
}
