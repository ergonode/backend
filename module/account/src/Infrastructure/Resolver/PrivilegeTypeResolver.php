<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Resolver;

use Ergonode\Account\Domain\ValueObject\Privilege;

class PrivilegeTypeResolver implements PrivilegeTypeResolverInterface
{
    private const SEPARATOR = '_';

    /**
     * {@inheritDoc}
     */
    public function resolve(Privilege $privilege): string
    {
        $value = $privilege->getValue();

        if (false === strpos($value, self::SEPARATOR)) {
            throw new \InvalidArgumentException('Separator not found');
        }

        return strtolower(substr(strrchr($privilege->getValue(), self::SEPARATOR), 1));
    }
}
