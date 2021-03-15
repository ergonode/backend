<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure;

use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\SharedKernel\Domain\AggregateId;

interface DomainEventFactoryInterface
{
    /**
     * @param array $records
     *
     * @return DomainEventEnvelope[]
     */
    public function create(AggregateId $id, array $records): array;
}
