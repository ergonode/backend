<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Money\Currency;

/**
 * Class CurrencyFaker
 */
class CurrencyFaker extends BaseProvider
{
    public function currency(string $currency = null): Currency
    {
        if (null === $currency) {
            $currencies = ['RUB', 'USD', 'EUR', 'GBP', 'PLN'];
            $random = array_rand($currencies);
            $currency = $currencies[$random];
        }

        return new Currency($currency);
    }
}
