<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Provider;

interface RoleProviderInterface
{
    /**
     * @return array
     */
    public function getRoles(): array;
}
