<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;
use Ergonode\Designer\Domain\Event\TemplateDefaultImageAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateDefaultImageChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateDefaultImageRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateDefaultLabelAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateDefaultLabelChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateDefaultLabelRemovedEvent;
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
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class Template extends AbstractAggregateRoot
{
    private TemplateId $id;

    private string $name;

    private ?MultimediaId $imageId;

    private TemplateGroupId $groupId;

    private ?AttributeId $defaultLabel;

    private ?AttributeId $defaultImage;

    /**
     * @var TemplateElementInterface[]
     */
    private array $elements;

    public function __construct(
        TemplateId $id,
        TemplateGroupId $groupId,
        string $name,
        ?AttributeId $defaultLabel = null,
        ?AttributeId $defaultImage = null,
        ?MultimediaId $imageId = null
    ) {
        $this->apply(new TemplateCreatedEvent($id, $groupId, $name, $defaultLabel, $defaultImage, $imageId));
    }

    public function getId(): TemplateId
    {
        return $this->id;
    }

    public function getGroupId(): TemplateGroupId
    {
        return $this->groupId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefaultLabel(): ?AttributeId
    {
        return $this->defaultLabel;
    }

    /**
     * @throws \Exception
     */
    public function addDefaultLabel(AttributeId $defaultLabel): void
    {
        if ($this->defaultLabel) {
            throw new \RuntimeException('Template default label already added');
        }

        $this->apply(new TemplateDefaultLabelAddedEvent($this->id, $defaultLabel));
    }

    public function removeDefaultLabel(): void
    {
        if (!$this->defaultLabel) {
            throw new \RuntimeException('Template default label not exists');
        }

        $this->apply(new TemplateDefaultLabelRemovedEvent($this->id, $this->defaultLabel));
    }

    public function changeDefaultLabel(AttributeId $newDefaultLabel): void
    {
        if (!$this->defaultLabel->isEqual($newDefaultLabel)) {
            $this->apply(new TemplateDefaultLabelChangedEvent(
                $this->id,
                $newDefaultLabel,
            ));
        }
    }

    /**
     * @throws \Exception
     */
    public function addDefaultImage(AttributeId $defaultImage): void
    {
        if ($this->defaultImage) {
            throw new \RuntimeException('Template default image already added');
        }

        $this->apply(new TemplateDefaultImageAddedEvent($this->id, $defaultImage));
    }

    public function removeDefaultImage(): void
    {
        if (!$this->defaultImage) {
            throw new \RuntimeException('Template default image not exists');
        }

        $this->apply(new TemplateDefaultImageRemovedEvent($this->id, $this->defaultImage));
    }

    public function changeDefaultImage(AttributeId $newDefaultImage): void
    {
        if (!$this->defaultImage->isEqual($newDefaultImage)) {
            $this->apply(new TemplateDefaultImageChangedEvent(
                $this->id,
                $newDefaultImage,
            ));
        }
    }

    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

    public function hasElement(Position $position): bool
    {
        foreach ($this->elements as $element) {
            if ($position->isEqual($element->getPosition())) {
                return true;
            }
        }

        return false;
    }

    public function getElement(Position $position): TemplateElementInterface
    {
        foreach ($this->elements as $element) {
            if ($position->isEqual($element->getPosition())) {
                return $element;
            }
        }

        $message = \sprintf('There is no element on position %sx%s', $position->getX(), $position->getY());
        throw new \InvalidArgumentException($message);
    }

    /**
     * @return ArrayCollection|TemplateElementInterface[]
     */
    public function getElements(): ArrayCollection
    {
        return new ArrayCollection(array_values($this->elements));
    }

    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }

    /**
     * @throws \Exception
     */
    public function changeName(string $name): void
    {
        if ($name !== $this->name) {
            $this->apply(new TemplateNameChangedEvent($this->id, $name));
        }
    }

    /**
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
     * @throws \Exception
     */
    public function changeImage(MultimediaId $imageId): void
    {
        if (!$imageId->isEqual($this->imageId)) {
            $this->apply(new TemplateImageChangedEvent($this->id, $imageId));
        }
    }

    public function removeImage(): void
    {
        if (!$this->imageId) {
            throw new \RuntimeException('Template image not exists');
        }

        $this->apply(new TemplateImageRemovedEvent($this->id, $this->imageId));
    }

    /**
     * @throws \Exception
     */
    public function changeGroup(TemplateGroupId $groupId): void
    {
        if (!$groupId->isEqual($this->groupId)) {
            $this->apply(new TemplateGroupChangedEvent($this->id, $groupId));
        }
    }

    /**
     * @throws \Exception
     */
    public function addElement(TemplateElementInterface $element): void
    {
        $position = $element->getPosition();
        if ($this->hasElement($element->getPosition())) {
            $message = \sprintf('There is element on position %sx%s', $position->getX(), $position->getY());
            throw new \InvalidArgumentException($message);
        }

        $this->apply(new TemplateElementAddedEvent($this->id, $element));
    }

    /**
     * @throws \Exception
     */
    public function changeElement(TemplateElementInterface $element): void
    {
        $position = $element->getPosition();

        if (!$this->hasElement($element->getPosition())) {
            $message = \sprintf('There is no element on position %sx%s', $position->getX(), $position->getY());
            throw new \InvalidArgumentException($message);
        }

        $this->apply(new TemplateElementChangedEvent($this->id, $element));
    }

    /**
     * @throws \Exception
     */
    public function removeElement(Position $position): void
    {
        $element = $this->getElement($position);

        $this->apply(new TemplateElementRemovedEvent($this->id, $element->getPosition()));
    }

    protected function applyTemplateNameChangedEvent(TemplateNameChangedEvent $event): void
    {
        $this->name = $event->getTo();
    }

    protected function applyTemplateGroupChangedEvent(TemplateGroupChangedEvent $event): void
    {
        $this->groupId = $event->getNew();
    }

    protected function applyTemplateElementAddedEvent(TemplateElementAddedEvent $event): void
    {
        $this->elements[] = $event->getElement();
    }

    protected function applyTemplateElementChangedEvent(TemplateElementChangedEvent $event): void
    {
        $element = $event->getElement();
        $position = $element->getPosition();
        foreach ($this->elements as $key => $element) {
            if ($position->isEqual($element->getPosition())) {
                $this->elements[$key] = $event->getElement();
            }
        }
    }

    protected function applyTemplateElementRemovedEvent(TemplateElementRemovedEvent $event): void
    {
        foreach ($this->elements as $key => $element) {
            if ($event->getPosition()->isEqual($element->getPosition())) {
                unset($this->elements[$key]);
            }
        }
    }

    protected function applyTemplateCreatedEvent(TemplateCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
        $this->defaultLabel = $event->getDefaultLabel();
        $this->defaultImage = $event->getDefaultImage();
        $this->imageId = $event->getImageId();
        $this->groupId = $event->getGroupId();
        $this->elements = [];
    }

    protected function applyTemplateImageAddedEvent(TemplateImageAddedEvent $event): void
    {
        $this->imageId = $event->getImageId();
    }


    protected function applyTemplateDefaultLabelAddedEvent(TemplateDefaultLabelAddedEvent $event): void
    {
        $this->defaultLabel = $event->getDefaultLabel();
    }

    protected function applyTemplateDefaultImageAddedEvent(TemplateDefaultImageAddedEvent $event): void
    {
        $this->defaultImage = $event->getDefaultImage();
    }

    protected function applyTemplateImageChangedEvent(TemplateImageChangedEvent $event): void
    {
        $this->imageId = $event->getTo();
    }

    protected function applyTemplateImageRemovedEvent(TemplateImageRemovedEvent $event): void
    {
        $this->imageId = null;
    }

    protected function applyTemplateDefaultLabelRemovedEvent(TemplateDefaultLabelRemovedEvent $event): void
    {
        $this->defaultLabel = null;
    }

    protected function applyTemplateDefaultImageRemovedEvent(TemplateDefaultImageRemovedEvent $event): void
    {
        $this->defaultImage = null;
    }

    protected function applyTemplateDefaultLabelChangedEvent(TemplateDefaultLabelChangedEvent $event): void
    {
        $this->defaultLabel = $event->getTo();
    }

    protected function applyTemplateDefaultImageChangedEvent(TemplateDefaultImageChangedEvent $event): void
    {
        $this->defaultImage = $event->getTo();
    }
}
