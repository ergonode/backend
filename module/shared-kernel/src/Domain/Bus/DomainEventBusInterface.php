<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Bus;

use Ergonode\SharedKernel\Domain\DomainEventInterface;

interface DomainEventBusInterface
{
    public function dispatch(DomainEventInterface $event): void;
}
