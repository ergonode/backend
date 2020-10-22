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
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

class PrivilegeDictionaryProvider
{
    /**
     * @var PrivilegeQueryInterface
     */
    private PrivilegeQueryInterface $query;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var PrivilegeTypeResolverInterface
     */
    private PrivilegeTypeResolverInterface $resolver;

    /**
     * @param PrivilegeQueryInterface        $query
     * @param TranslatorInterface            $translator
     * @param PrivilegeTypeResolverInterface $resolver
     */
    public function __construct(
        PrivilegeQueryInterface $query,
        TranslatorInterface $translator,
        PrivilegeTypeResolverInterface $resolver
    ) {
        $this->query = $query;
        $this->translator = $translator;
        $this->resolver = $resolver;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        $records = $this->query->getPrivileges();
        foreach ($records as $record) {
            $privilege = new Privilege($record['code']);
            $privilegeType = $this->resolver->resolve($privilege);
            $result[$record['area']]['name'] =
                $this->translator->trans($record['area'], [], 'privilege', $language->getCode());
            $result[$record['area']]['description'] =
                $this->translator->trans($record['description'], [], 'privilege', $language->getCode());
            $result[$record['area']]['privileges'][$privilegeType] = $privilege;
        }

        return array_values($result);
    }
}
