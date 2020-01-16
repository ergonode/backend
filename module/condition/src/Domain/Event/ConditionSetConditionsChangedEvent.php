<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\Entity\AbstractId;
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
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $id;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     */
    private $from;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     */
    private $to;

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
     * @return AbstractId|ConditionSetId
     */
    public function getAggregateId(): AbstractId
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
