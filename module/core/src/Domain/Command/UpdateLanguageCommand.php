<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

class UpdateLanguageCommand implements CoreCommandInterface
{
    /**
     * @var Language[]
     */
    private array $languages;

    /**
     * @param Language[] $languages
     */
    public function __construct(array $languages)
    {
        Assert::allIsInstanceOf($languages, Language::class);

        $this->languages = $languages;
    }

    /**
     * @return array<Language>
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }
}
