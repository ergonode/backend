<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Provider;

use Ergonode\Account\Domain\Query\RoleQueryInterface;

class RoleProvider implements RoleProviderInterface
{
    private RoleQueryInterface $roleQuery;

    public function __construct(RoleQueryInterface $roleQuery)
    {
        $this->roleQuery = $roleQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        return $this->roleQuery->getDictionary();
    }
}
