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
     * @param string|null $languageCode
     * @param bool|null   $read
     * @param bool|null   $edit
     *
     * @return array|LanguagePrivileges[]
     */
    public function languagePrivilegesCollection(?string $languageCode = 'en_US', ?bool $read = true, ?bool $edit = true): array
    {

        return [$languageCode => new LanguagePrivileges($read, $edit)];
    }
}
