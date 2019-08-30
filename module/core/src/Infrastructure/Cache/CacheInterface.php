<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Cache;

use Ramsey\Uuid\UuidInterface;

/**
 */
interface CacheInterface
{
    /**
     * @param UuidInterface $key
     *
     * @return string|null
     */
    public function get(UuidInterface $key): ?string;

    /**
     * @param UuidInterface $key
     * @param string        $data
     */
    public function set(UuidInterface $key, string $data): void;

    /**
     * @param UuidInterface $key
     *
     * @return bool
     */
    public function has(UuidInterface $key): bool;
}
