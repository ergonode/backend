<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Resolver;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class TranslatedOptionValueResolver
{
    /**
     * @param OptionInterface $option
     * @param Language        $language
     *
     * @return string|null
     */
    public function resolve(OptionInterface $option, Language $language): ?string
    {
        if ($option instanceof MultilingualOption) {
            return $option->getValue()->get($language);
        }

        if ($option instanceof StringOption) {
            return $option->getValue();
        }

        throw new \RuntimeException(sprintf('Can\'t resolve translation for %s', get_class($option)));
    }
}
