<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Event\Status\StatusColorChangedEvent;
use Ergonode\Workflow\Domain\Event\Status\StatusCreatedEvent;
use Ergonode\Workflow\Domain\Event\Status\StatusDescriptionChangedEvent;
use Ergonode\Workflow\Domain\Event\Status\StatusNameChangedEvent;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

class Status extends AbstractAggregateRoot
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $color;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
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

    public function getId(): StatusId
    {
        return $this->id;
    }

    public function getCode(): StatusCode
    {
        return $this->code;
    }

    public function getColor(): Color
    {
        return $this->color;
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
            $this->apply(new StatusNameChangedEvent($this->id, $name));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $description): void
    {
        if (!$description->isEqual($this->description)) {
            $this->apply(new StatusDescriptionChangedEvent($this->id, $description));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeColor(Color $color): void
    {
        if (!$color->isEqual($this->color)) {
            $this->apply(new StatusColorChangedEvent($this->id, $color));
        }
    }

    protected function applyStatusCreatedEvent(StatusCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->color = $event->getColor();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
    }

    protected function applyStatusNameChangedEvent(StatusNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    protected function applyStatusDescriptionChangedEvent(StatusDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }

    protected function applyStatusColorChangedEvent(StatusColorChangedEvent $event): void
    {
        $this->color = $event->getTo();
    }
}
