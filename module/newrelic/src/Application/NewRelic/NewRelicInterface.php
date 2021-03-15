<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Application\NewRelic;

interface NewRelicInterface
{
    public function startTransaction(?string $license = null): bool;
    public function endTransaction(bool $ignore = false): bool;
    public function nameTransaction(string $name): bool;
}
