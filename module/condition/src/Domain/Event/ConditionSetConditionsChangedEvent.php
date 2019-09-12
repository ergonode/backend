<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class ConditionSetConditionsChangedEvent implements DomainEventInterface
{
    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\Condition\ConditionInterface>")
     */
    private $from;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\Condition\ConditionInterface>")
     */
    private $to;

    /**
     * @param array $from
     * @param array $to
     */
    public function __construct(array $from, array $to)
    {
        Assert::allIsInstanceOf($from, ConditionInterface::class);
        Assert::allIsInstanceOf($to, ConditionInterface::class);

        $this->from = $from;
        $this->to = $to;
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
