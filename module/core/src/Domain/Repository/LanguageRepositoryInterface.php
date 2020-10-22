<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\Core\Domain\ValueObject\Language;

interface LanguageRepositoryInterface
{
    /**
     * @param Language $language
     * @param bool     $active
     */
    public function save(Language $language, bool $active): void;

    /**
     * @param Language $language
     *
     * @return bool
     */
    public function exists(Language $language): bool;
}
