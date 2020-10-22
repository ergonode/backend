<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Mapper;

use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

class LanguageMapper
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
     * @param Language   $language
     * @param Language[] $allLanguages
     *
     * @return array
     */
    public function map(Language $language, array $allLanguages): array
    {
        $result = [];
        foreach ($allLanguages as $availableLanguage) {
            $code = $availableLanguage->getCode();
            $result[$code] = $this->translator->trans($code, [], 'language', $language->getCode());
        }

        asort($result);

        return $result;
    }
}
