<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Infrastructure\Provider;

use Ergonode\Core\Domain\ValueObject\Language;

interface TranslationProviderInterface
{
    public function provide(string $content, Language $sourceLanguage, Language $targetLanguage): string;
}
