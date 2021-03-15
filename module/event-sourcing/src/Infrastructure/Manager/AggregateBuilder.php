<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Infrastructure\Manager;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\AggregateId;

class AggregateBuilder implements AggregateBuilderInterface
{
    /**
     * @throws \ReflectionException
     */
    public function build(AggregateId $id, string $class): ?AbstractAggregateRoot
    {
        $reflection = new \ReflectionClass($class);
        /** @var AbstractAggregateRoot $aggregate */
        $aggregate = $reflection->newInstanceWithoutConstructor();

        return $aggregate;
    }
}
