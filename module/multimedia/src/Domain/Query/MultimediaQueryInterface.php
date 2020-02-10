<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\ValueObject\Hash;

/**
 */
interface MultimediaQueryInterface
{
    /**
     * @param Hash $hash
     *
     * @return bool
     */
    public function fileExists(Hash $hash): bool;

    /**
     * @param Hash $hash
     *
     * @return MultimediaId|null
     */
    public function findIdByHash(Hash $hash): ?MultimediaId;
}
