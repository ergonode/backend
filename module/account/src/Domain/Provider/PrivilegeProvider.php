<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Provider;

use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;

/**
 */
class PrivilegeProvider
{
    /**
     * @var PrivilegeQueryInterface
     */
    private $query;

    /**
     * @param PrivilegeQueryInterface $query
     */
    public function __construct(PrivilegeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @return Privilege[]
     */
    public function provide(): array
    {
        $result = [];
        foreach ($this->query->getPrivileges() as $row) {
            $result[] = $row['code'];
        }

        return $result;
    }
}
