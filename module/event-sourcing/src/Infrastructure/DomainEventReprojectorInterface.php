<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\SharedKernel\Domain\AggregateId;

interface DomainEventReprojectorInterface
{
    public function reproject(AggregateId $aggregateId, array $eventClasses = []): void;
}
