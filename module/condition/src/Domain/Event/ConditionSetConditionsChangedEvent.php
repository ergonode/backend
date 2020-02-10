<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class ConditionSetConditionsChangedEvent implements DomainEventInterface
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ConditionSetId $id;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     */
    private array $from;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     */
    private array $to;

    /**
     * @param ConditionSetId $id
     * @param array          $from
     * @param array          $to
     */
    public function __construct(ConditionSetId $id, array $from, array $to)
    {
        Assert::allIsInstanceOf($from, ConditionInterface::class);
        Assert::allIsInstanceOf($to, ConditionInterface::class);

        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return ConditionSetId
     */
    public function getAggregateId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getTo(): array
    {
        return $this->to;
    }
}
