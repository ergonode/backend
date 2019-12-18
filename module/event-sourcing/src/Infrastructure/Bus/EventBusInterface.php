<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure\Bus;

use Ergonode\EventSourcing\Infrastructure\DomainAggregateEventInterface;

/**
 */
interface EventBusInterface
{
    /**
     * @param DomainAggregateEventInterface $event
     */
    public function dispatch(DomainAggregateEventInterface $event): void;
}
