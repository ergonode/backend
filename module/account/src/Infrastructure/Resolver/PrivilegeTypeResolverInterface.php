<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Resolver;

use Ergonode\Account\Domain\ValueObject\Privilege;

interface PrivilegeTypeResolverInterface
{
    public function resolve(Privilege $privilege): string;
}
