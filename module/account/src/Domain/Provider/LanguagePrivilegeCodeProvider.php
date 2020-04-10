<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Provider;

use Ergonode\Account\Domain\Query\LanguagePrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\LanguagePrivilege;

/**
 */
class LanguagePrivilegeCodeProvider
{
    /**
     * @var LanguagePrivilegeQueryInterface
     */
    private LanguagePrivilegeQueryInterface $query;

    /**
     * @param LanguagePrivilegeQueryInterface $query
     */
    public function __construct(LanguagePrivilegeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @return LanguagePrivilege[]
     */
    public function provide(): array
    {
        return array_column($this->query->getLanguagePrivileges(), 'code');
    }
}
