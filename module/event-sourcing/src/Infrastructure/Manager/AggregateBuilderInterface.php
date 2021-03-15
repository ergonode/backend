<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;

interface AggregateBuilderInterface
{
    /**
     * @throws \ReflectionException
     */
    public function build(AggregateId $id, string $class): ?AbstractAggregateRoot;
}
