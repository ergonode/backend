<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

abstract class AbstractStringBasedChangedEvent implements AggregateEventInterface
{
    private ?string $to;

    public function __construct(?string $to)
    {
        $this->to = $to;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }
}
