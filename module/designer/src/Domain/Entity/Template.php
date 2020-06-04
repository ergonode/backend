<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

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
use JMS\Serializer\Annotation as JMS;

/**
 */
class Template extends AbstractAggregateRoot
{
    /**
     * @var TemplateId
     */
    private TemplateId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var MultimediaId | null
     */
    private ?MultimediaId $imageId;

    /**
     * @var TemplateGroupId
     */
    private TemplateGroupId $groupId;

    /**
     * @var AttributeId | null
     */
    private ?AttributeId $defaultLabel;

    /**
     * @var AttributeId | null
     */
    private ?AttributeId $defaultImage;

    /**
     * @var TemplateElement[]
     *
     * @JMS\Type("array<Ergonode\Designer\Domain\Entity\TemplateElement>")
     */
    private array $elements;

    /**
     * @param TemplateId        $id
     * @param TemplateGroupId   $groupId
     * @param string            $name
     * @param AttributeId       $defaultLabel
     * @param AttributeId       $defaultImage
     * @param MultimediaId|null $imageId
     *
     * @throws \Exception
     */
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
     * @return AttributeId | null
     */
    public function getDefaultLabel(): ?AttributeId
    {
        return $this->defaultLabel;
    }

    /**
     * @param AttributeId $defaultLabel
     *
     * @throws \Exception
     */
    public function addDefaultLabel(AttributeId $defaultLabel): void
    {
        if ($this->defaultLabel) {
            throw new \RuntimeException('Template default label already added');
        }

        $this->apply(new TemplateDefaultLabelAddedEvent($this->id, $defaultLabel));
    }

    /**
     */
    public function removeDefaultLabel(): void
    {
        if (!$this->defaultLabel) {
            throw new \RuntimeException('Template default label not exists');
        }

        $this->apply(new TemplateDefaultLabelRemovedEvent($this->id, $this->defaultLabel));
    }

    /**
     * @param AttributeId $newDefaultLabel
     */
    public function changeDefaultLabel(AttributeId $newDefaultLabel): void
    {
        if (!$this->defaultLabel->isEqual($newDefaultLabel)) {
            $this->apply(new TemplateDefaultLabelChangedEvent(
                $this->id,
                $this->getDefaultLabel(),
                $newDefaultLabel,
            ));
        }
    }

    /**
     * @param AttributeId $defaultImage
     *
     * @throws \Exception
     */
    public function addDefaultImage(AttributeId $defaultImage): void
    {
        if ($this->defaultImage) {
            throw new \RuntimeException('Template default image already added');
        }

        $this->apply(new TemplateDefaultImageAddedEvent($this->id, $defaultImage));
    }

    /**
     */
    public function removeDefaultImage(): void
    {
        if (!$this->defaultImage) {
            throw new \RuntimeException('Template default image not exists');
        }

        $this->apply(new TemplateDefaultImageRemovedEvent($this->id, $this->defaultImage));
    }

    /**
     * @param AttributeId $newDefaultImage
     */
    public function changeDefaultImage(AttributeId $newDefaultImage): void
    {
        if (!$this->defaultImage->isEqual($newDefaultImage)) {
            $this->apply(new TemplateDefaultImageChangedEvent(
                $this->id,
                $this->getDefaultImage(),
                $newDefaultImage,
            ));
        }
    }

    /**
     * @return AttributeId | null
     */
    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
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
        $this->defaultLabel = $event->getDefaultLabel();
        $this->defaultImage = $event->getDefaultImage();
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
     * @param TemplateDefaultLabelAddedEvent $event
     */
    protected function applyTemplateDefaultLabelAddedEvent(TemplateDefaultLabelAddedEvent $event): void
    {
        $this->defaultLabel = $event->getDefaultLabel();
    }

    /**
     * @param TemplateDefaultImageAddedEvent $event
     */
    protected function applyTemplateDefaultImageAddedEvent(TemplateDefaultImageAddedEvent $event): void
    {
        $this->defaultImage = $event->getDefaultImage();
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
     * @param TemplateDefaultLabelRemovedEvent $event
     */
    protected function applyTemplateDefaultLabelRemovedEvent(TemplateDefaultLabelRemovedEvent $event): void
    {
        $this->defaultLabel = null;
    }

    /**
     * @param TemplateDefaultImageRemovedEvent $event
     */
    protected function applyTemplateDefaultImageRemovedEvent(TemplateDefaultImageRemovedEvent $event): void
    {
        $this->defaultImage = null;
    }

    /**
     * @param TemplateDefaultLabelChangedEvent $event
     */
    protected function applyTemplateDefaultLabelChangedEvent(
        TemplateDefaultLabelChangedEvent $event
    ): void {
        $this->defaultLabel = $event->getTo();
    }

    /**
     * @param TemplateDefaultImageChangedEvent $event
     */
    protected function applyTemplateDefaultImageChangedEvent(
        TemplateDefaultImageChangedEvent $event
    ): void {
        $this->defaultImage = $event->getTo();
    }
}
