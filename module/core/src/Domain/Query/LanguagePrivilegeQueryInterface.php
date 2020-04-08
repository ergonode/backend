<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Query;

/**
 */
interface LanguagePrivilegeQueryInterface
{
    /**
     * @return array
     */
    public function getLanguagePrivileges(): array;
}
