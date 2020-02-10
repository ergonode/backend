<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Segment\Domain\Event\SegmentConditionSetChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentNameChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class Segment extends AbstractAggregateRoot
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     * @JMS\Expose()
     */
    private SegmentId $id;

    /**
     * @var SegmentStatus
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     * @JMS\Expose()
     */
    private SegmentStatus $status;

    /**
     * @var SegmentCode
     *
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentCode")
     * @JMS\Expose()
     */
    private SegmentCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     * @JMS\Expose()
     */
    private TranslatableString $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     * @JMS\Expose()
     */
    private TranslatableString $description;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     * @JMS\Expose()
     */
    private ?ConditionSetId $conditionSetId;

    /**
     * @param SegmentId           $id
     * @param SegmentCode         $code
     * @param TranslatableString  $name
     * @param TranslatableString  $description
     * @param ConditionSetId|null $conditionSetId
     *
     * @throws \Exception
     */
    public function __construct(
        SegmentId $id,
        SegmentCode $code,
        TranslatableString $name,
        TranslatableString $description,
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->status = new SegmentStatus(SegmentStatus::NEW);
        $this->apply(new SegmentCreatedEvent($id, $code, $name, $description, $this->status, $conditionSetId));
    }

    /**
     * @return SegmentId
     */
    public function getId(): SegmentId
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
            $this->apply(new SegmentStatusChangedEvent($this->id, $this->status, $status));
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
            $this->apply(new SegmentNameChangedEvent($this->id, $this->name, $name));
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
            $this->apply(new SegmentDescriptionChangedEvent($this->id, $this->description, $description));
        }
    }

    /**
     * @return bool
     */
    public function hasConditionSet(): bool
    {
        return null !== $this->conditionSetId;
    }

    /**
     * @param ConditionSetId $conditionSetId
     *
     * @throws \Exception
     */
    public function changeConditionSet(?ConditionSetId $conditionSetId = null): void
    {
        if (null !== $this->conditionSetId || null !== $conditionSetId) {
            $this->apply(new SegmentConditionSetChangedEvent($this->id, $this->conditionSetId, $conditionSetId));
        }
    }

    /**
     * @param SegmentCreatedEvent $event
     */
    protected function applySegmentCreatedEvent(SegmentCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
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
