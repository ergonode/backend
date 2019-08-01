<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Resolver;

use Ergonode\Account\Domain\ValueObject\Privilege;

/**
 */
interface PrivilegeTypeResolverInterface
{
    /**
     * @param Privilege $privilege
     *
     * @return string
     */
    public function resolve(Privilege $privilege): string;
}
