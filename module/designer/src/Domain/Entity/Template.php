<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Core\Domain\ValueObject\State;
use Ergonode\Designer\Domain\Event\TemplateElementBecomeNonRequiredEvent;
use Ergonode\Designer\Domain\Event\TemplateElementBecomeRequiredEvent;
use Ergonode\Designer\Domain\Event\TemplateElementRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementResizedEvent;
use Ergonode\Designer\Domain\Event\TemplateGroupChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateImageRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateNameChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateRemovedEvent;
use Ergonode\Designer\Domain\Event\TemplateSectionAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateSectionChangedEvent;
use Ergonode\Designer\Domain\Event\TemplateSectionRemovedEvent;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Webmozart\Assert\Assert;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\Designer\Domain\Event\TemplateCreatedEvent;
use Ergonode\Designer\Domain\Event\TemplateElementMovedEvent;
use Ergonode\Designer\Domain\ValueObject\Position;
use Ergonode\Designer\Domain\ValueObject\Size;

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
     * @var ArrayCollection|TemplateElement[]
     */
    private $elements;

    /**
     * @var ArrayCollection|string[]
     */
    private $sections;

    /**
     * @var State
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
     * @param TemplateElementId $id
     *
     * @return bool
     */
    public function hasElement(TemplateElementId $id): bool
    {
        return $this->elements->containsKey($id->getValue());
    }

    /**
     * @param TemplateElementId $id
     *
     * @return TemplateElement
     */
    public function getElement(TemplateElementId $id): TemplateElement
    {
        $this->validateElementExists($id);

        return $this->elements->get($id->getValue());
    }



    /**
     * @return ArrayCollection
     */
    public function getElements(): ArrayCollection
    {
        return clone $this->elements;
    }

    /**
     * @param int $row
     *
     * @return string
     */
    public function getSection(int $row): string
    {
        Assert::greaterThanEq($row, 0);

        $this->validateSectionExists($row);

        return (string) $this->sections->get($row);
    }

    /**
     * @param int $row
     *
     * @return bool
     */
    public function hasSection(int $row): bool
    {
        Assert::greaterThanEq($row, 0);

        return $this->sections->containsKey($row);
    }

    /**
     * @return ArrayCollection
     */
    public function getSections(): ArrayCollection
    {
        return clone $this->sections;
    }

    /**
     * @return MultimediaId
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
        $this->apply(new TemplateNameChangedEvent($this->name, $name));
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
     * @param int    $row
     * @param string $section
     */
    public function addSection(int $row, string $section): void
    {
        if ($this->hasSection($row)) {
            throw new \InvalidArgumentException(\sprintf('Section for column %s already exists', $row));
        }

        $this->apply(new TemplateSectionAddedEvent($row, $section));
    }

    /**
     * @param int    $row
     * @param string $section
     */
    public function changeSection(int $row, string $section): void
    {
        Assert::greaterThanEq($row, 0);

        $this->validateSectionExists($row);

        if ($this->sections[$row] !== $section) {
            $this->apply(new TemplateSectionChangedEvent($row, $this->sections[$row], $section));
        }
    }

    /**
     * @param int $row
     */
    public function removeSection(int $row): void
    {
        Assert::greaterThanEq($row, 0);

        $this->validateSectionExists($row);

        $this->apply(new TemplateSectionRemovedEvent($row));
    }

    /**
     * @param TemplateElementId $id
     * @param Position          $position
     * @param Size              $size
     * @param bool              $required
     */
    public function addElement(TemplateElementId $id, Position $position, Size $size, bool $required = false): void
    {
        if ($this->hasElement($id)) {
            throw new \InvalidArgumentException(\sprintf('Attribute already %s exists', $id->getValue()));
        }

        $this->apply(new TemplateElementAddedEvent($id, $position, $size, $required));
    }

    /**
     * @param TemplateElementId $id
     * @param Position          $position
     */
    public function moveElement(TemplateElementId $id, Position $position): void
    {
        $this->validateElementExists($id);

        $element = $this->elements[$id->getValue()];

        if (!$element->getPosition()->isEqual($position)) {
            $this->apply(new TemplateElementMovedEvent($id, $element->getPosition(), $position));
        }
    }

    /**
     * @param TemplateElementId $id
     * @param Size              $size
     */
    public function resizeElement(TemplateElementId $id, Size $size): void
    {
        $this->validateElementExists($id);

        $element = $this->elements[$id->getValue()];

        if (!$element->getSize()->isEqual($size)) {
            $this->apply(new TemplateElementResizedEvent($id, $element->getSize(), $size));
        }
    }

    /**
     * @param TemplateElementId $id
     */
    public function makeRequired(TemplateElementId $id): void
    {
        $this->validateElementExists($id);
        if (!$this->elements[$id->getValue()]->isRequired()) {
            $this->apply(new TemplateElementBecomeRequiredEvent($id));
        }
    }

    /**
     * @param TemplateElementId $id
     */
    public function makeNonRequired(TemplateElementId $id): void
    {
        $this->validateElementExists($id);
        if ($this->elements[$id->getValue()]->isRequired()) {
            $this->apply(new TemplateElementBecomeNonRequiredEvent($id));
        }
    }

    /**
     * @param TemplateElementId $id
     */
    public function removeElement(TemplateElementId $id): void
    {
        $this->validateElementExists($id);

        $this->apply(new TemplateElementRemovedEvent($id));
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
        $this->elements->set($event->getElementId()->getValue(), new TemplateElement($event->getElementId(), $event->getPosition(), $event->getSize(), $event->isRequired()));
    }

    /**
     * @param TemplateElementMovedEvent $event
     */
    protected function applyTemplateElementMovedEvent(TemplateElementMovedEvent $event): void
    {
        $this->elements[$event->getElementId()->getValue()]->setPosition($event->getTo());
    }

    /**
     * @param TemplateElementResizedEvent $event
     */
    protected function applyTemplateElementResizedEvent(TemplateElementResizedEvent $event): void
    {
        $this->elements[$event->getElementId()->getValue()]->setSize($event->getTo());
    }

    /**
     * @param TemplateElementRemovedEvent $event
     */
    protected function applyTemplateElementRemovedEvent(TemplateElementRemovedEvent $event): void
    {
        unset($this->elements[$event->getElementId()->getValue()]);
    }

    /**
     * @param TemplateElementBecomeRequiredEvent $event
     */
    protected function applyTemplateElementBecomeRequiredEvent(TemplateElementBecomeRequiredEvent $event): void
    {
        $this->elements[$event->getElementId()->getValue()]->setRequired(true);
    }

    /**
     * @param TemplateElementBecomeNonRequiredEvent $event
     */
    protected function applyTemplateElementBecomeNonRequiredEvent(TemplateElementBecomeNonRequiredEvent $event): void
    {
        $this->elements[$event->getElementId()->getValue()]->setRequired(false);
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
        $this->elements = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->state = new State();
    }

    /**
     * @param TemplateSectionAddedEvent $event
     */
    protected function applyTemplateSectionAddedEvent(TemplateSectionAddedEvent $event): void
    {
        $this->sections->set($event->getRow(), $event->getSection());
    }

    /**
     * @param TemplateSectionChangedEvent $event
     */
    protected function applyTemplateSectionChangedEvent(TemplateSectionChangedEvent $event): void
    {
        $this->sections->set($event->getRow(), $event->getTo());
    }

    /**
     * @param TemplateSectionRemovedEvent $event
     */
    protected function applyTemplateSectionRemovedEvent(TemplateSectionRemovedEvent $event): void
    {
        $this->sections->remove($event->getRow());
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
        $this->imageId = $event->getImageId();
    }

    /**
     * @param TemplateRemovedEvent $event
     */
    protected function applyTemplateRemovedEvent(TemplateRemovedEvent $event): void
    {
        $this->state = new State(State::STATE_DELETED);
    }

    /**
     * @param TemplateElementId $id
     */
    private function validateElementExists(TemplateElementId $id): void
    {
        if (!$this->hasElement($id)) {
            throw new \InvalidArgumentException(\sprintf('Attribute %s not found', $id->getValue()));
        }
    }

    /**
     * @param int $row
     */
    private function validateSectionExists(int $row): void
    {
        if (!$this->hasSection($row)) {
            throw new \InvalidArgumentException(\sprintf('Section at row %s not found', $row));
        }
    }
}
