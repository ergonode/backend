<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\LanguageNode;

class LanguageTree
{
    /**
     * @var LanguageNode
     */
    private LanguageNode $languages;

    /**
     * @param LanguageNode $rootLanguage
     */
    public function __construct(LanguageNode $rootLanguage)
    {
        $this->languages = $rootLanguage;
    }

    /**
     * @return LanguageNode
     */
    public function getLanguages(): LanguageNode
    {
        return $this->languages;
    }

    /**
     * @param LanguageNode $languages
     */
    public function updateLanguages(LanguageNode $languages): void
    {
        $this->languages = $languages;
    }
}
