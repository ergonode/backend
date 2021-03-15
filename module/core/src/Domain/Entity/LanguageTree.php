<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\LanguageNode;

class LanguageTree
{
    private LanguageNode $languages;

    public function __construct(LanguageNode $rootLanguage)
    {
        $this->languages = $rootLanguage;
    }

    public function getLanguages(): LanguageNode
    {
        return $this->languages;
    }

    public function updateLanguages(LanguageNode $languages): void
    {
        $this->languages = $languages;
    }
}
