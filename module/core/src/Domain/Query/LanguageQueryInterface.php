<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Query;

/**
 */
interface LanguageQueryInterface
{
    /**
     * @return array
     */
    public function getLanguages(): array;

    /**
     * @return array
     */
    public function getSystemLanguages(): array;
}
