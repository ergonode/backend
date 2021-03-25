<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Repository;

use Ergonode\Core\Domain\ValueObject\Language;

interface LanguageRepositoryInterface
{
    public function save(Language $language, bool $active): void;

    public function exists(Language $language): bool;
}
