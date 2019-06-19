<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Domain\ValueObject\State;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateNameChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;
use Ergonode\Designer\Domain\ValueObject\Position;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Template extends AbstractAggregateRoot
{
    /**
     * @var TemplateId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var MultimediaId
     */
    private $imageId;

    /**
     * @var TemplateGroupId
     */
    private $groupId;

    /**
     * @var TemplateElement[]
     *
     * @JMS\Type("array<Ergonode\Designer\Domain\Entity\TemplateElement>")
     */
    private $elements;

    /**
     * @var State
     *
     * @JMS\Exclude()
     */
    private $state;

    /**
     * @param TemplateId        $id
     * @param TemplateGroupId   $groupId
     * @param string            $name
     * @param MultimediaId|null $imageId
     */
    public function __construct(TemplateId $id, TemplateGroupId $groupId, string $name, ?MultimediaId $imageId = null)
    {
        $this->apply(new TemplateCreatedEvent($id, $groupId, $name, $imageId));
    }

    /**
     * @return AbstractId|TemplateId
     */
    public function getId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return TemplateGroupId
     */
    public function getGroupId(): TemplateGroupId
    {
        return $this->groupId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Position $position
     *
     * @return bool
     */
    public function hasElement(Position $position): bool
    {
        return isset($this->elements[(string) $position]);
    }

    /**
     * @param Position $position
     *
     * @return TemplateElement
     */
    public function getElement(Position $position): TemplateElement
    {
        if (!$this->hasElement($position)) {
            throw new \InvalidArgumentException(\sprintf('There is no element on position %sx%s', $position->getX(), $position->getY()));
        }

        return $this->elements[(string) $position];
    }

    /**
     * @return ArrayCollection|TemplateElement
     */
    public function getElements(): ArrayCollection
    {
        return new ArrayCollection(array_values($this->elements));
    }

    /**
     * @return MultimediaId|null
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }

    /**
     * @param string $name
     */
    public function changeName(string $name): void
    {
        if ($name !== $this->name) {
            $this->apply(new TemplateNameChangedEvent($this->name, $name));
        }
    }

    /**
     *
     */
    public function remove(): void
    {
        if ($this->state->getValue() !== State::STATE_DELETED) {
            $this->apply(new TemplateRemovedEvent());
        }
    }

    /**
     * @param MultimediaId $imageId
     */
    public function addImage(MultimediaId $imageId): void
    {
        if ($this->imageId) {
            throw new \RuntimeException('Template image already added');
        }

        $this->apply(new TemplateImageAddedEvent($imageId));
    }

    /**
     * @param MultimediaId $imageId
     */
    public function changeImage(MultimediaId $imageId): void
    {
        if ($this->imageId->getValue() !== $imageId->getValue()) {
            $this->apply(new TemplateImageChangedEvent($this->imageId, $imageId));
        }
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->state->getValue() === State::STATE_DELETED;
    }

    /**
     */
    public function removeImage(): void
    {
        if (!$this->imageId) {
            throw new \RuntimeException('Template image not exists');
        }

        $this->apply(new TemplateImageRemovedEvent($this->imageId));
    }

    /**
     * @param TemplateGroupId $groupId
     */
    public function changeGroup(TemplateGroupId $groupId): void
    {
        $this->apply(new TemplateGroupChangedEvent($this->groupId, $groupId));
    }

    /**
     * @param TemplateElement $element
     */
    public function addElement(TemplateElement $element): void
    {
        $position = $element->getPosition();
        if ($this->hasElement($element->getPosition())) {
            throw new \InvalidArgumentException(\sprintf('There is already element on position %sx%s', $position->getX(), $position->getY()));
        }

        $this->apply(new TemplateElementAddedEvent($element));
    }

    /**
     * @param TemplateElement $element
     */
    public function changeElement(TemplateElement $element): void
    {
        $position = $element->getPosition();

        if (!$this->hasElement($element->getPosition())) {
            throw new \InvalidArgumentException(\sprintf('There is no element on position %sx%s', $position->getX(), $position->getY()));
        }

        $this->apply(new TemplateElementChangedEvent($element));
    }

    /**
     * @param Position $position
     */
    public function removeElement(Position $position): void
    {
        $element = $this->getElement($position);

        $this->apply(new TemplateElementRemovedEvent($element->getPosition()));
    }

    /**
     * @param TemplateNameChangedEvent $event
     */
    protected function applyTemplateNameChangedEvent(TemplateNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    /**
     * @param TemplateGroupChangedEvent $event
     */
    protected function applyTemplateGroupChangedEvent(TemplateGroupChangedEvent $event): void
    {
        $this->groupId = $event->getNew();
    }

    /**
     * @param TemplateElementAddedEvent $event
     */
    protected function applyTemplateElementAddedEvent(TemplateElementAddedEvent $event): void
    {
        $element = $event->getElement();
        $position = $element->getPosition();
        $this->elements[(string) $position] = $event->getElement();
    }

    /**
     * @param TemplateElementChangedEvent $event
     */
    protected function applyTemplateElementChangedEvent(TemplateElementChangedEvent $event): void
    {
        $element = $event->getElement();
        $position = $element->getPosition();
        $this->elements[(string) $position] = $event->getElement();
    }

    /**
     * @param TemplateElementRemovedEvent $event
     */
    protected function applyTemplateElementRemovedEvent(TemplateElementRemovedEvent $event): void
    {
        $position = (string) $event->getPosition();
        unset($this->elements[$position]);
    }

    /**
     * @param TemplateCreatedEvent $event
     */
    protected function applyTemplateCreatedEvent(TemplateCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
        $this->imageId = $event->getImageId();
        $this->groupId = $event->getGroupId();
        $this->elements = [];
        $this->state = new State();
    }

    /**
     * @param TemplateImageAddedEvent $event
     */
    protected function applyTemplateImageAddedEvent(TemplateImageAddedEvent $event): void
    {
        $this->imageId = $event->getImageId();
    }

    /**
     * @param TemplateImageChangedEvent $event
     */
    protected function applyTemplateImageChangedEvent(TemplateImageChangedEvent $event): void
    {
        $this->imageId = $event->getTo();
    }

    /**
     * @param TemplateImageRemovedEvent $event
     */
    protected function applyTemplateImageRemovedEvent(TemplateImageRemovedEvent $event): void
    {
        $this->imageId = null;
    }

    /**
     * @param TemplateRemovedEvent $event
     */
    protected function applyTemplateRemovedEvent(TemplateRemovedEvent $event): void
    {
        $this->state = new State(State::STATE_DELETED);
    }
}
