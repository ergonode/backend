<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\Annotation as JMS;

/**
 */
class SegmentCreatedEvent implements DomainEventInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentCode")
     */
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $description;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     */
    private $status;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $conditionSetId;

    /**
     * @param SegmentId           $id
     * @param SegmentCode         $code
     * @param TranslatableString  $name
     * @param TranslatableString  $description
     * @param SegmentStatus       $status
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(
        SegmentId $id,
        SegmentCode $code,
        TranslatableString $name,
        TranslatableString $description,
        SegmentStatus $status,
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->conditionSetId = $conditionSetId;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
    }

    /**
     * @return SegmentId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return SegmentCode
     */
    public function getCode(): SegmentCode
    {
        return $this->code;
    }

    /**
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    /**
     * @return SegmentStatus
     */
    public function getStatus(): SegmentStatus
    {
        return $this->status;
    }
}
