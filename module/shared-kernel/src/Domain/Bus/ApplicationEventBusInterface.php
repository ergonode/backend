<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Bus;

use Ergonode\SharedKernel\Application\ApplicationEventInterface;

interface ApplicationEventBusInterface
{
    public function dispatch(ApplicationEventInterface $event): void;
}
