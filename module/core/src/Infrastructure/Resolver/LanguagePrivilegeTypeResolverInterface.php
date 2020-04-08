<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Resolver;

use Ergonode\Core\Domain\ValueObject\LanguagePrivilege;

/**
 */
interface LanguagePrivilegeTypeResolverInterface
{
    /**
     * @param LanguagePrivilege $languagePrivilege
     *
     * @return string
     */
    public function resolve(LanguagePrivilege $languagePrivilege): string;
}
