<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Query;

use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Domain\ValueObject\PrivilegeEndPoint;

interface PrivilegeQueryInterface
{
    /**
     * @return array
     */
    public function getPrivileges(bool $hidden = false): array;

    public function getPrivilegesEndPoint(): array;

    /**
     * @param Privilege[] $privileges
     *
     * @return PrivilegeEndPoint[]
     */
    public function getEndpointPrivilegesByPrivileges(array $privileges): array;
}
