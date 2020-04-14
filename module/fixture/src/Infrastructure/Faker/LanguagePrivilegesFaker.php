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
    /**
     * @param array|null $array
     *
     * @return LanguagePrivileges
     */
    public function languagePrivileges(?array $array = []): LanguagePrivileges
    {
        if (empty($array)) {
            return new LanguagePrivileges(['read' => [], 'edit' => []]);
        }

        return new LanguagePrivileges($array);
    }
}
