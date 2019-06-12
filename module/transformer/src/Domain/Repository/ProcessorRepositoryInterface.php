<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Transformer\Domain\Entity\Processor;
use Ergonode\Transformer\Domain\Entity\ProcessorId;

/**
 */
interface ProcessorRepositoryInterface
{
    /**
     * @param ProcessorId $id
     *
     * @return AbstractAggregateRoot|Processor
     * @throws \ReflectionException
     */
    public function load(ProcessorId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;
}
