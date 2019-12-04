<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ConditionSetCreatedEvent implements DomainEventInterface
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $id;

    /**
     * @var array
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     */
    private $conditions;

    /**
     * @param ConditionSetId $id
     * @param array          $conditions
     */
    public function __construct(ConditionSetId $id, array $conditions = [])
    {
        $this->id = $id;
        $this->conditions = $conditions;
    }

    /**
     * @return ConditionSetId
     */
    public function getId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
