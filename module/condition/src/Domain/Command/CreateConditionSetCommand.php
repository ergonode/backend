<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Command;

use Ergonode\Condition\Domain\ConditionInterface;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateConditionSetCommand implements DomainCommandInterface
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private ConditionSetId $id;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\ConditionInterface>")
     */
    private array $conditions;

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
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
