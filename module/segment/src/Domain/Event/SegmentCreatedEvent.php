<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;

class SegmentCreatedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentCode")
     */
    private SegmentCode $code;

    private TranslatableString $name;

    private TranslatableString $description;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $conditionSetId;

    public function __construct(
        SegmentId $id,
        SegmentCode $code,
        TranslatableString $name,
        TranslatableString $description,
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->conditionSetId = $conditionSetId;
        $this->name = $name;
        $this->description = $description;
    }

    public function getAggregateId(): SegmentId
    {
        return $this->id;
    }

    public function getCode(): SegmentCode
    {
        return $this->code;
    }

    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }
}
