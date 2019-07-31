<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Infrastructure\Provider;

use Ergonode\Account\Domain\Query\RoleQueryInterface;

/**
 */
class RoleProvider implements RoleProviderInterface
{
    /**
     * @var RoleQueryInterface
     */
    private $roleQuery;

    /**
     * @param RoleQueryInterface $roleQuery
     */
    public function __construct(RoleQueryInterface $roleQuery)
    {
        $this->roleQuery = $roleQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        $this->roleQuery->getDictionary();
    }
}