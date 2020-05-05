<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Faker\Provider\Base as BaseProvider;

/**
 */
class LanguagePrivilegesFaker extends BaseProvider
{
    private const ACTIVE_LANGUAGE_CODES = [
        'en',
        'pl',
    ];

    /**
     * @param array $languageCodes
     *
     * @return array|LanguagePrivileges[]
     */
    public function languagePrivilegesCollection(array $languageCodes = self::ACTIVE_LANGUAGE_CODES): array
    {
        $result = [];
        foreach ($languageCodes as $languageCode) {
            $result[$languageCode] = new LanguagePrivileges(true, true);
        }

        return $result;
    }
}
