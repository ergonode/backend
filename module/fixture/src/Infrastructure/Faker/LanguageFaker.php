<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\Language;
use Faker\Provider\Base as BaseProvider;

/**
 */
class LanguageFaker extends BaseProvider
{
    private const ISO = [
        'en',
        'pl',
        'de',
        'es',
    ];

    /**
     * @param string|null $code
     *
     * @return Language
     *
     */
    public function language(string $code = null): Language
    {
        if (null === $code) {
            $languages = array_combine(self::ISO, self::ISO);
            $code = array_rand($languages);
        }

        return new Language($code);
    }
}
