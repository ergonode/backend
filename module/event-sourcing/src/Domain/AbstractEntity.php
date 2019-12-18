<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\EventSourcing\Domain;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
abstract class AbstractEntity
{
    /**
     * @var AbstractAggregateRoot|null
     */
    private $aggregateRoot;

    /**
     * @param DomainEventInterface $event
     *
     * @throws \Exception
     */
    public function apply(DomainEventInterface $event): void
    {
        $this->aggregateRoot->apply($event);
    }

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function setAggregateRoot(AbstractAggregateRoot $aggregateRoot): void
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    /**
     * @param DomainEventInterface $event
     * @param \DateTime            $recordedAt
     */
    public function handle(DomainEventInterface $event, \DateTime $recordedAt): void
    {
        $classArray = explode('\\', get_class($event));
        $class = end($classArray);
        $method = sprintf('apply%s', $class);
        if (method_exists($this, $method)) {
            $this->$method($event);
        }
    }
}
