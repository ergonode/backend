<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Provider;

use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;

class PrivilegeCodeProvider
{
    private PrivilegeQueryInterface $query;

    public function __construct(PrivilegeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @return Privilege[]
     */
    public function provide(): array
    {
        return array_column($this->query->getPrivileges(true), 'code');
    }
}
