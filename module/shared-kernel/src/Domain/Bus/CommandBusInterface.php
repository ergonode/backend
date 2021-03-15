<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Bus;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;

interface CommandBusInterface
{
    public function dispatch(DomainCommandInterface $command, bool $asyncMode = false): void;
}
