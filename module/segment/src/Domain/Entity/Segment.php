<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
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
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class Segment extends AbstractAggregateRoot
{
    private SegmentId $id;

    private SegmentCode $code;

    private TranslatableString $name;

    private TranslatableString $description;

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
        $this->apply(new SegmentCreatedEvent($id, $code, $name, $description, $conditionSetId));
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

    /**
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$name->isEqual($this->name)) {
            $this->apply(new SegmentNameChangedEvent($this->id, $name));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $description): void
    {
        if (!$description->isEqual($this->description)) {
            $this->apply(new SegmentDescriptionChangedEvent($this->id, $description));
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
            $this->apply(new SegmentConditionSetChangedEvent($this->id, $conditionSetId));
        }
    }

    protected function applySegmentCreatedEvent(SegmentCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->conditionSetId = $event->getConditionSetId();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
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
