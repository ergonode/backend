<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Entity;


use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Product\Domain\Event\Status\ProductStatusColorChangedEvent;
use Ergonode\Product\Domain\Event\Status\ProductStatusCreatedEvent;
use Ergonode\Product\Domain\Event\Status\ProductStatusDescriptionChangedEvent;
use Ergonode\Product\Domain\Event\Status\ProductStatusNameChangedEvent;
use Ergonode\Product\Domain\Event\Status\ProductStatusTransitionAddedEvent;
use Ergonode\Product\Domain\ValueObject\ProductStatusTransition;

/**
 */
class ProductStatus extends AbstractAggregateRoot
{
    /**
     * @var ProductStatusId
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var TranslatableString
     */
    private $name;

    /**
     * @var TranslatableString
     */
    private $description;

    /**
     * @var Color
     */
    private $color;

    /**
     * @var ProductStatusTransition[]
     */
    private $transitions;

    /**
     * @param ProductStatusId           $id
     * @param string                    $code
     * @param Color                     $color
     * @param TranslatableString        $name
     * @param TranslatableString        $description
     *
     * @throws \Exception
     */
    public function __construct(
        ProductStatusId $id,
        string $code,
        Color $color,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->apply(new ProductStatusCreatedEvent($id, $code, $color, $name, $description));
    }

    /**
     * @return ProductStatusId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @param Color $color
     *
     * @throws \Exception
     */
    public function changeColor(Color $color): void
    {
        if(!$color->isEqual($this->color)) {
            $this->apply(new ProductStatusColorChangedEvent($this->color, $color));
        }
    }

    /**
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function changeName(TranslatableString $name): void
    {
        if(!$name->isEqual($this->name)) {
            $this->apply(new ProductStatusNameChangedEvent($this->name, $name));
        }
    }

    /**
     * @param TranslatableString $description
     *
     * @throws \Exception
     */
    public function changeDescription(TranslatableString $description): void
    {
        if(!$description->isEqual($this->description)) {
            $this->apply(new ProductStatusNameChangedEvent($this->description, $description));
        }
    }

    /**
     * @param string                  $code
     * @param ProductStatusTransition $transition
     *
     * @throws \Exception
     *
     */
    public function addTransition(string $code, ProductStatusTransition $transition): void
    {
        assertArrayHasKey($code, $this->transitions, sprintf('Transition %s already exists', $code));

        $this->apply(new ProductStatusTransitionAddedEvent($code, $transition));
    }

    /**
     * @return ProductStatusTransition[]
     */
    public function getTransitions(): array
    {
        return $this->transitions;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function hasTransition(string $code): bool
    {
        return array_key_exists($code, $this->transitions);
    }

    /**
     * @param ProductStatusCreatedEvent $event
     */
    private function applyProductStatusCreatedEvent(ProductStatusCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->code = $event->getCode();
        $this->name = $event->getName();
        $this->description = $event->getDescription();
        $this->color = $event->getColor();
    }

    /**
     * @param ProductStatusColorChangedEvent $event
     */
    private function applyProductStatusColorChangedEvent(ProductStatusColorChangedEvent $event): void
    {
        $this->color = $event->getTo();
    }

    /**
     * @param ProductStatusNameChangedEvent $event
     */
    private function applyProductStatusNameChangedEvent(ProductStatusNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param ProductStatusDescriptionChangedEvent $event
     */
    private function applyProductStatusDescriptionChangedEvent(ProductStatusDescriptionChangedEvent $event): void
    {
        $this->description = $event->getTo();
    }
}
