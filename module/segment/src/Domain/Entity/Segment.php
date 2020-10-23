<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

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

class Segment extends AbstractAggregateRoot
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentStatus")
     */
    private SegmentStatus $status;

    /**
     * @JMS\Type("Ergonode\Segment\Domain\ValueObject\SegmentCode")
     */
    private SegmentCode $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $conditionSetId;

    /**
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

    public function getId(): SegmentId
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

    public function getStatus(): SegmentStatus
    {
        return $this->status;
    }

    /**
     * @throws \Exception
     */
    public function changeStatus(SegmentStatus $status): void
    {
        if (!$status->isEqual($this->status)) {
            $this->apply(new SegmentStatusChangedEvent($this->id, $this->status, $status));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$name->isEqual($this->name)) {
            $this->apply(new SegmentNameChangedEvent($this->id, $this->name, $name));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $description): void
    {
        if (!$description->isEqual($this->description)) {
            $this->apply(new SegmentDescriptionChangedEvent($this->id, $this->description, $description));
        }
    }

    public function hasConditionSet(): bool
    {
        return null !== $this->conditionSetId;
    }

    /**
     * @throws \Exception
     */
    public function changeConditionSet(?ConditionSetId $conditionSetId = null): void
    {
        if (null !== $this->conditionSetId || null !== $conditionSetId) {
            $this->apply(new SegmentConditionSetChangedEvent($this->id, $this->conditionSetId, $conditionSetId));
        }
    }

    protected function applySegmentCreatedEvent(SegmentCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->conditionSetId = $event->getConditionSetId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->status = $event->getStatus();
    }

    protected function applySegmentStatusChangedEvent(SegmentStatusChangedEvent $event): void
    {
        $this->status = $event->getTo();
    }

    protected function applySegmentNameChangedEvent(SegmentNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    protected function applySegmentDescriptionChangedEvent(SegmentDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }

    protected function applySegmentConditionSetChangedEvent(SegmentConditionSetChangedEvent $event): void
    {
        $this->conditionSetId = $event->getTo();
    }
}
