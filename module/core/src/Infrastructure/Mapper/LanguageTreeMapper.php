<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Mapper;

use Ergonode\Account\Domain\ValueObject\LanguagePrivileges;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LanguageTreeMapper
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param Language             $language
     * @param array                $treeLanguages
     * @param LanguagePrivileges[] $privileges
     *
     * @return array
     */
    public function map(Language $language, array $treeLanguages, array $privileges): array
    {
        $result = [];
        $defaultPrivilege = new LanguagePrivileges(false, false);
        foreach ($treeLanguages as $treeLanguage) {
            $code = $treeLanguage['code'];
            $result[$code] = array_merge(
                $treeLanguage,
                [
                    'name' => $this->translator->trans($code, [], 'language', $language->getCode()),
                    'privileges' => isset($privileges[$code]) ? $privileges[$code] : $defaultPrivilege,
                ]
            );
        }

        return $result;
    }
}
