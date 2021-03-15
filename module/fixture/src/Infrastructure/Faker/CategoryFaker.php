<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;

class CategoryFaker extends BaseProvider
{
    public const CATEGORIES = [
        'bluzki',
        'bluzy',
        'getry',
        'legginsy',
        'golfy',
        'kamizelki',
        'koszule',
        'kurtki',
        'płaszcze',
        'spodnie',
        'spódnice',
        'sukienki',
        'tuniki',
        'swetry',
        'szorty',
        't-shirty',
        'topy',
        'żakiety',
        'Akcesoria',
        'biżuteria',
        'breloczki',
        'buty',
        'chusty',
        'szaliki',
        'czapki',
        'kapelusze',
        'okulary',
        'paski',
        'portfele',
        'rękawiczki',
        'torebki',
    ];

    public function category(): string
    {
        $random = array_rand(self::CATEGORIES);

        return self::CATEGORIES[$random];
    }
}
