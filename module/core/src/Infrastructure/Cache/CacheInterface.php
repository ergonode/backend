<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Cache;

use Ramsey\Uuid\UuidInterface;

interface CacheInterface
{
    public function get(UuidInterface $key): ?string;

    public function set(UuidInterface $key, string $data): void;

    public function has(UuidInterface $key): bool;
}
