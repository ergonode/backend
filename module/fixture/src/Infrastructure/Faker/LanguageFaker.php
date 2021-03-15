<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\Language;
use Faker\Provider\Base as BaseProvider;

class LanguageFaker extends BaseProvider
{
    private const ISO = [
        'en_GB',
        'pl_PL',
        'de_DE',
        'es_ES',
    ];

    public function language(string $code = null): Language
    {
        if (null === $code) {
            $languages = array_combine(self::ISO, self::ISO);
            $code = array_rand($languages);
        }

        return new Language($code);
    }
}
