<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Workflow\Domain\Event\Status\StatusColorChangedEvent;
use Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent;
use Ergonode\Workflow\Domain\Event\Status\StatusDescriptionChangedEvent;
use Ergonode\Workflow\Domain\Event\Status\StatusNameChangedEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Status extends AbstractAggregateRoot
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $id;

    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $code;

    /**
     * @var Color
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private $color;

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
     * @param StatusId           $id
     * @param StatusCode         $code
     * @param Color              $color
     * @param TranslatableString $name
     * @param TranslatableString $description
     *
     * @throws \Exception
     */
    public function __construct(
        StatusId $id,
        StatusCode $code,
        Color $color,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->apply(new StatusCreatedEvent($id, $code, $color, $name, $description));
    }

    /**
     * @return StatusId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return StatusCode
     */
    public function getCode(): StatusCode
    {
        return $this->code;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
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
            $this->apply(new StatusNameChangedEvent($this->name, $name));
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
            $this->apply(new StatusDescriptionChangedEvent($this->description, $description));
        }
    }

    /**
     * @param Color $color
     *
     * @throws \Exception
     */
    public function changeColor(Color $color): void
    {
        if (!$color->isEqual($this->color)) {
            $this->apply(new StatusColorChangedEvent($this->color, $color));
        }
    }

    /**
     * @param StatusCreatedEvent $event
     */
    protected function applyStatusCreatedEvent(StatusCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->color = $event->getColor();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
    }

    /**
     * @param StatusNameChangedEvent $event
     */
    protected function applyStatusNameChangedEvent(StatusNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param StatusDescriptionChangedEvent $event
     */
    protected function applyStatusDescriptionChangedEvent(StatusDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }

    /**
     * @param StatusColorChangedEvent $event
     */
    protected function applyStatusColorChangedEvent(StatusColorChangedEvent $event): void
    {
        $this->color = $event->getTo();
    }
}
