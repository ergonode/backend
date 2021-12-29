<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\EventSourcing\Domain;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;

abstract class AbstractEntity
{
    protected ?AbstractAggregateRoot $aggregateRoot;

    /**
     * @throws \Exception
     */
    public function apply(AggregateEventInterface $event): void
    {
        $this->aggregateRoot->apply($event);
    }

    public function setAggregateRoot(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    public function handle(AggregateEventInterface $event, \DateTime $recordedAt): void
    {
        $classArray = explode('\\', get_class($event));
        $class = end($classArray);
        $method = sprintf('apply%s', $class);
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }
}
