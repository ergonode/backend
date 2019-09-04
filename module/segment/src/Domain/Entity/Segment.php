<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Entity;

use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Segment\Domain\Event\SegmentDescriptionChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentNameChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentStatusChangedEvent;
use Ergonode\Segment\Domain\Event\SegmentConditionAddedEvent;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Segment\Domain\Event\SegmentCreatedEvent;
use Ergonode\Segment\Domain\Condition\ConditionInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentStatus;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

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
     * @var ConditionInterface[]
     */
    private $specifications;

    /**
     * @param SegmentId          $id
     * @param SegmentCode        $code
     * @param TranslatableString $name
     *
     * @param TranslatableString $description
     *
     * @throws \Exception
     */
    public function __construct(SegmentId $id, SegmentCode $code, TranslatableString $name, TranslatableString $description)
    {
        $this->apply(new SegmentCreatedEvent($id, $code, $name, $description));
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
     * @param ConditionInterface $specification
     *
     * @throws \Exception
     */
    public function addCondition(ConditionInterface $specification): void
    {
        $this->apply(new SegmentConditionAddedEvent($specification));
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->specifications;
    }

    /**
     * @param SegmentCreatedEvent $event
     */
    protected function applySegmentCreatedEvent(SegmentCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->status = new SegmentStatus();
        $this->specifications = [];
    }

    /**
     * @param SegmentConditionAddedEvent $event
     */
    protected function applySegmentConditionAddedEvent(SegmentConditionAddedEvent $event): void
    {
        $this->specifications[] = $event->getCondition();
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
}
