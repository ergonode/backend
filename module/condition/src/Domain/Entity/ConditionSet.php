<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Entity;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Event\ConditionSetConditionAddedEvent;
use Ergonode\Condition\Domain\Event\ConditionSetCreatedEvent;
use Ergonode\Condition\Domain\Event\ConditionSetDescriptionChangedEvent;
use Ergonode\Condition\Domain\Event\ConditionSetNameChangedEvent;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class ConditionSet extends AbstractAggregateRoot
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     * @JMS\Expose()
     */
    private $id;

      /**
     * @var ConditionSetCode
     *
     * @JMS\Type("Ergonode\Condition\Domain\ValueObject\ConditionSetCode")
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
    private $conditions;

    /**
     * @param ConditionSetId     $id
     * @param ConditionSetCode   $code
     * @param TranslatableString $name
     * @param TranslatableString $description
     *
     * @throws \Exception
     */
    public function __construct(
        ConditionSetId $id,
        ConditionSetCode $code,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->apply(new ConditionSetCreatedEvent($id, $code, $name, $description));
    }

    /**
     * @return ConditionSetId|AbstractId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return ConditionSetCode
     */
    public function getCode(): ConditionSetCode
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
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if (!$name->isEqual($this->name)) {
            $this->apply(new ConditionSetNameChangedEvent($this->name, $name));
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
            $this->apply(new ConditionSetDescriptionChangedEvent($this->description, $description));
        }
    }

    /**
     * @param ConditionInterface $specification
     *
     * @throws \Exception
     */
    public function addCondition(ConditionInterface $specification): void
    {
        $this->apply(new ConditionSetConditionAddedEvent($specification));
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * @param ConditionSetCreatedEvent $event
     */
    protected function applyConditionSetCreatedEvent(ConditionSetCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->conditions = [];
    }

    /**
     * @param ConditionSetConditionAddedEvent $event
     */
    protected function applyConditionSetConditionAddedEvent(ConditionSetConditionAddedEvent $event): void
    {
        $this->conditions[] = $event->getCondition();
    }

    /**
     * @param ConditionSetNameChangedEvent $event
     */
    protected function applyConditionSetNameChangedEvent(ConditionSetNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param ConditionSetDescriptionChangedEvent $event
     */
    protected function applyConditionSetDescriptionChangedEvent(ConditionSetDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }
}
