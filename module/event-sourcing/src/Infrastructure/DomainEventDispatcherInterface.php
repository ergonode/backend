<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;

/**
 */
interface DomainEventDispatcherInterface
{
    /**
     * @param DomainEventEnvelope $even
     */
    public function dispatch(DomainEventEnvelope $even): void;
}
