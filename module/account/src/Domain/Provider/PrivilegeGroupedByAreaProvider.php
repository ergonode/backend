<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Provider;

use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Infrastructure\Resolver\PrivilegeTypeResolverInterface;

class PrivilegeGroupedByAreaProvider
{
    /**
     * @var PrivilegeQueryInterface
     */
    private PrivilegeQueryInterface $query;

    /**
     * @var PrivilegeTypeResolverInterface
     */
    private PrivilegeTypeResolverInterface $resolver;

    /**
     * @param PrivilegeQueryInterface        $query
     * @param PrivilegeTypeResolverInterface $resolver
     */
    public function __construct(
        PrivilegeQueryInterface $query,
        PrivilegeTypeResolverInterface $resolver
    ) {
        $this->query = $query;
        $this->resolver = $resolver;
    }

    /**
     * @return array
     */
    public function provide(): array
    {
        $result = [];
        $privileges = $this->query->getPrivileges(true);
        foreach ($privileges as $record) {
            $privilegeType = $this->resolver->resolve(new Privilege($record['code']));
            $result[$record['area']][$privilegeType] = $record['code'];
        }

        return $result;
    }
}
