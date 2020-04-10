<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Resolver;

use Ergonode\Account\Domain\ValueObject\LanguagePrivilege;

/**
 */
class LanguagePrivilegeTypeResolver implements LanguagePrivilegeTypeResolverInterface
{
    private const SEPARATOR = '_';

    /**
     * {@inheritDoc}
     */
    public function resolve(LanguagePrivilege $languagePrivilege): string
    {
        $value = $languagePrivilege->getValue();

        if (false === strpos($value, self::SEPARATOR)) {
            throw new \InvalidArgumentException('Separator not found');
        }

        return strtolower(substr(strrchr($languagePrivilege->getValue(), self::SEPARATOR), 1));
    }
}
