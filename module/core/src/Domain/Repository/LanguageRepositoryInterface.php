<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
interface LanguageRepositoryInterface
{
    /**
     * @param array $codes
     *
     * @return array
     */
    public function load(array $codes): array;

    /**
     * @param Language $languageCode
     * @param bool     $active
     */
    public function save(Language $languageCode, bool $active): void;

    /**
     * @param Language $languageCode
     *
     * @return bool
     */
    public function exists(Language $languageCode): bool;
}
