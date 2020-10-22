<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

class DeleteConditionSetCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ConditionSetId $id;

    public function __construct(ConditionSetId $id)
    {
        $this->id = $id;
    }

    public function getId(): ConditionSetId
    {
        return $this->id;
    }
}
