<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Query;

interface PrivilegeQueryInterface
{
    /**
     * @param bool $hidden
     *
     * @return array
     */
    public function getPrivileges(bool $hidden = false): array;
}
