<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain;

interface AggregateEventInterface extends DomainEventInterface
{
    public function getAggregateId(): AggregateId;
}
