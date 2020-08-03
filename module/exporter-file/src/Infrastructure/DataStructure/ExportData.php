<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\DataStructure;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class ExportData
{
    /**
     * @var LanguageData[]
     */
    private array $languages = [];

    /**
     * @param Language     $language
     * @param LanguageData $data
     */
    public function set(Language $language, LanguageData $data): void
    {
        $this->languages[$language->getCode()] = $data;
    }

    /**
     * @return LanguageData[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }
}
