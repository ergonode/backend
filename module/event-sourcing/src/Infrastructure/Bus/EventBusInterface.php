<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;

interface EventBusInterface
{
    public function dispatch(DomainEventInterface $event): void;
}
