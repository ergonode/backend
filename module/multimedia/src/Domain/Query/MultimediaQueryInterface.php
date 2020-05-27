<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Query;

use Ergonode\Multimedia\Domain\ValueObject\Hash;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

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

    /**
     * @return array
     */
    public function getMultimedia(): array;
}
