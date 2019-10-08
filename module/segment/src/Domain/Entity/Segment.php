<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Entity;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentNameChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Segment extends AbstractAggregateRoot
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\Segment\Domain\Entity\SegmentId")
     * @JMS\Expose()
     */
    private $id;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     * @JMS\Expose()
     */
    private $status;

    /**
     * @var SegmentCode
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentCode")
     * @JMS\Expose()
     */
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     * @JMS\Expose()
     */
    private $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     * @JMS\Expose()
     */
    private $description;

    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     * @JMS\Expose()
     */
    private $conditionSetId;

    /**
     * @param SegmentId          $id
     * @param SegmentCode        $code
     * @param ConditionSetId     $conditionSetId
     * @param TranslatableString $name
     * @param TranslatableString $description
     *
     * @throws \Exception
     */
    public function __construct(
        SegmentId $id,
        SegmentCode $code,
        ConditionSetId $conditionSetId,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->status = new SegmentStatus(SegmentStatus::NEW);
        $this->apply(new SegmentCreatedEvent($id, $code, $conditionSetId, $name, $description, $this->status));
    }

    /**
     * @return SegmentId|AbstractId
     */
    public function getId(): AbstractId
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
     * @return ConditionSetId
     */
    public function getConditionSetId(): ConditionSetId
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

    /**
     * @param SegmentStatus $status
     *
     * @throws \Exception
     */
    public function changeStatus(SegmentStatus $status): void
    {
        if (!$status->isEqual($this->status)) {
            $this->apply(new SegmentStatusChangedEvent($this->status, $status));
        }
    }

    /**
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$name->isEqual($this->name)) {
            $this->apply(new SegmentNameChangedEvent($this->name, $name));
        }
    }

    /**
     * @param TranslatableString $description
     *
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $description): void
    {
        if (!$description->isEqual($this->description)) {
            $this->apply(new SegmentDescriptionChangedEvent($this->description, $description));
        }
    }

    /**
     * @param ConditionSetId $conditionSetId
     *
     * @throws \Exception
     */
    public function changeConditionSet(ConditionSetId $conditionSetId): void
    {
        if (!$conditionSetId->isEqual($this->conditionSetId)) {
            $this->apply(new SegmentConditionSetChangedEvent($this->conditionSetId, $conditionSetId));
        }
    }

    /**
     * @param SegmentCreatedEvent $event
     */
    protected function applySegmentCreatedEvent(SegmentCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->conditionSetId = $event->getConditionSetId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->status = $event->getStatus();
    }

    /**
     * @param SegmentStatusChangedEvent $event
     */
    protected function applySegmentStatusChangedEvent(SegmentStatusChangedEvent $event): void
    {
        $this->status = $event->getTo();
    }

    /**
     * @param SegmentNameChangedEvent $event
     */
    protected function applySegmentNameChangedEvent(SegmentNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param SegmentDescriptionChangedEvent $event
     */
    protected function applySegmentDescriptionChangedEvent(SegmentDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }

    /**
     * @param SegmentConditionSetChangedEvent $event
     */
    protected function applySegmentConditionSetChangedEvent(SegmentConditionSetChangedEvent $event): void
    {
        $this->conditionSetId = $event->getTo();
    }
}
