<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\Language;
use Faker\Provider\Base as BaseProvider;

/**
 * Class LanguageFaker
 */
class LanguageFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return Language
     *
     */
    public function language(string $code = null): Language
    {
        if (null === $code) {
            $languages = array_combine(Language::AVAILABLE, Language::AVAILABLE);
            $code = array_rand($languages);
        }

        return new Language($code);
    }
}
