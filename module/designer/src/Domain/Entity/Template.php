<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateNameChangedEvent;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
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
     * @param TemplateId        $id
     * @param TemplateGroupId   $groupId
     * @param string            $name
     * @param MultimediaId|null $imageId
     *
     * @throws \Exception
     */
    public function __construct(TemplateId $id, TemplateGroupId $groupId, string $name, ?MultimediaId $imageId = null)
    {
        $this->apply(new TemplateCreatedEvent($id, $groupId, $name, $imageId));
    }

    /**
     * @return TemplateId
     */
    public function getId(): TemplateId
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
            $message = \sprintf('There is no element on position %sx%s', $position->getX(), $position->getY());
            throw new \InvalidArgumentException($message);
        }

        return $this->elements[(string) $position];
    }

    /**
     * @return ArrayCollection|TemplateElement[]
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
     *
     * @throws \Exception
     */
    public function changeName(string $name): void
    {
        if ($name !== $this->name) {
            $this->apply(new TemplateNameChangedEvent($this->id, $this->name, $name));
        }
    }

    /**
     * @param MultimediaId $imageId
     *
     * @throws \Exception
     */
    public function addImage(MultimediaId $imageId): void
    {
        if ($this->imageId) {
            throw new \RuntimeException('Template image already added');
        }

        $this->apply(new TemplateImageAddedEvent($this->id, $imageId));
    }

    /**
     * @param MultimediaId $imageId
     *
     * @throws \Exception
     */
    public function changeImage(MultimediaId $imageId): void
    {
        if (!$imageId->isEqual($this->imageId)) {
            $this->apply(new TemplateImageChangedEvent($this->id, $this->imageId, $imageId));
        }
    }

    /**
     */
    public function removeImage(): void
    {
        if (!$this->imageId) {
            throw new \RuntimeException('Template image not exists');
        }

        $this->apply(new TemplateImageRemovedEvent($this->id, $this->imageId));
    }

    /**
     * @param TemplateGroupId $groupId
     *
     * @throws \Exception
     */
    public function changeGroup(TemplateGroupId $groupId): void
    {
        if (!$groupId->isEqual($this->groupId)) {
            $this->apply(new TemplateGroupChangedEvent($this->id, $this->groupId, $groupId));
        }
    }

    /**
     * @param TemplateElement $element
     *
     * @throws \Exception
     */
    public function addElement(TemplateElement $element): void
    {
        $position = $element->getPosition();
        if ($this->hasElement($element->getPosition())) {
            $message = \sprintf('There is element on position %sx%s', $position->getX(), $position->getY());
            throw new \InvalidArgumentException($message);
        }

        $this->apply(new TemplateElementAddedEvent($this->id, $element));
    }

    /**
     * @param TemplateElement $element
     *
     * @throws \Exception
     */
    public function changeElement(TemplateElement $element): void
    {
        $position = $element->getPosition();

        if (!$this->hasElement($element->getPosition())) {
            $message = \sprintf('There is no element on position %sx%s', $position->getX(), $position->getY());
            throw new \InvalidArgumentException($message);
        }

        $this->apply(new TemplateElementChangedEvent($this->id, $element));
    }

    /**
     * @param Position $position
     *
     * @throws \Exception
     */
    public function removeElement(Position $position): void
    {
        $element = $this->getElement($position);

        $this->apply(new TemplateElementRemovedEvent($this->id, $element->getPosition()));
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
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->imageId = $event->getImageId();
        $this->groupId = $event->getGroupId();
        $this->elements = [];
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
}
