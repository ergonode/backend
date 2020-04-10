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
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LanguagePrivilegeDictionaryProvider
{
    /**
     * @var LanguagePrivilegeQueryInterface
     */
    private LanguagePrivilegeQueryInterface $query;

    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var LanguagePrivilegeTypeResolverInterface
     */
    private LanguagePrivilegeTypeResolverInterface $resolver;

    /**
     * @param LanguagePrivilegeQueryInterface        $query
     * @param TranslatorInterface                    $translator
     * @param LanguagePrivilegeTypeResolverInterface $resolver
     */
    public function __construct(
        LanguagePrivilegeQueryInterface $query,
        TranslatorInterface $translator,
        LanguagePrivilegeTypeResolverInterface $resolver
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
        $records = $this->query->getLanguagePrivileges();
        foreach ($records as $record) {
            $languagePrivilege = new LanguagePrivilege($record['code']);
            $languagePrivilegeType = $this->resolver->resolve($languagePrivilege);
            $result[$record['language']]['language'] = $record['language'];
            $result[$record['language']]['privileges'][$languagePrivilegeType] = $languagePrivilege->getValue();
        }

        return array_values($result);
    }
}
