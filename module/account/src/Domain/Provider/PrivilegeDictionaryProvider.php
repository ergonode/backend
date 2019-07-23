<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Provider;

use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class PrivilegeDictionaryProvider
{
    /**
     * @var PrivilegeQueryInterface
     */
    private $query;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param PrivilegeQueryInterface $query
     * @param TranslatorInterface     $translator
     */
    public function __construct(PrivilegeQueryInterface $query, TranslatorInterface $translator)
    {
        $this->query = $query;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function provide(Language $language): array
    {
        $result = [];
        foreach ($this->query->getPrivileges() as $record) {
            $result[$record['area']]['name'] = $this->translator->trans($record['area'], [], 'privilege', $language->getCode());
            $result[$record['area']]['privileges'][] = new Privilege($record['code']);
        }

        return array_values($result);
    }
}
