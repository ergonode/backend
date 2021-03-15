<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

class UpdateLanguageCommand implements CoreCommandInterface
{
    /**
     * @var array<Language>
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\Language>")
     */
    private array $languages;

    /**
     * @param array $languages
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
