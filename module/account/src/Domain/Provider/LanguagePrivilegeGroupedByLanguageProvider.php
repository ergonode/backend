<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Provider;

use Ergonode\Account\Domain\Query\LanguagePrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\LanguagePrivilege;
use Ergonode\Account\Infrastructure\Resolver\LanguagePrivilegeTypeResolverInterface;

/**
 */
class LanguagePrivilegeGroupedByLanguageProvider
{
    /**
     * @var LanguagePrivilegeQueryInterface
     */
    private LanguagePrivilegeQueryInterface $query;

    /**
     * @var LanguagePrivilegeTypeResolverInterface
     */
    private LanguagePrivilegeTypeResolverInterface $resolver;

    /**
     * @param LanguagePrivilegeQueryInterface        $query
     * @param LanguagePrivilegeTypeResolverInterface $resolver
     */
    public function __construct(
        LanguagePrivilegeQueryInterface $query,
        LanguagePrivilegeTypeResolverInterface $resolver
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
        $privileges = $this->query->getLanguagePrivileges();
        foreach ($privileges as $record) {
            $privilegeType = $this->resolver->resolve(new LanguagePrivilege($record['code']));
            $result[$record['language']][$privilegeType] = $record['code'];
        }

        return $result;
    }
}
