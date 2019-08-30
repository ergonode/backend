<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface TranslationProviderInterface
{
    /**
     * @param string   $content
     * @param Language $sourceLanguage
     * @param Language $targetLanguage
     *
     * @return string
     */
    public function provide(string $content, Language $sourceLanguage, Language $targetLanguage): string;
}
