<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Provider;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class LanguageProvider implements LanguageProviderInterface
{
    /**
     * @var LanguageQueryInterface
     */
    private $query;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param LanguageQueryInterface $query
     * @param TranslatorInterface    $translator
     */
    public function __construct(LanguageQueryInterface $query, TranslatorInterface $translator)
    {
        $this->query = $query;
        $this->translator = $translator;
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getLanguages(Language $language): array
    {
        return $this->map($language, $this->query->getLanguagesCodes());
    }

    /**
     * @param Language $language
     *
     * @return array
     */
    public function getActiveLanguages(Language $language): array
    {
        return $this->map($language, $this->query->getActiveLanguagesCodes());
    }

    /**
     * @param Language $language
     * @param array    $codes
     *
     * @return array
     */
    private function map(Language $language, array $codes): array
    {
        $result = [];
        foreach ($codes as $code) {
            $result[$code] = $this->translator->trans($code, [], 'language', strtolower($language->getCode()));
        }

        asort($result);

        return $result;
    }
}
