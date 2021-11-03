<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\Entity\AbstractOption;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionMovedEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionRemovedEvent;

abstract class AbstractOptionAttribute extends AbstractAttribute
{
    /**
     * @var AggregateId[]
     */
    private array $options = [];

    public function addOption(AbstractOption $option, bool $after = true, ?AbstractOption $position = null): self
    {
        if (!$this->hasOption($option->getId())) {
            if ($position) {
                $index = $after ? $this->getOptionIndex($position->getId()) + 1:$this->getOptionIndex($position->getId());
            } else {
                $index = $after ? count($this->options) : 0;
            }

            $this->apply(new AttributeOptionAddedEvent($this->id, $option->getId(), $index));
        }

        return $this;
    }

    public function moveOption(AbstractOption $option, bool $after = true, ?AbstractOption $position = null): self
    {
        if ($this->hasOption($option->getId())) {
            if ($position) {
                $index = $after ? $this->getOptionIndex($position->getId())+1:$this->getOptionIndex($position->getId())-1;
            } else {
                $index = $after ? count($this->options) : 0;
            }

            $this->apply(new AttributeOptionMovedEvent($this->id, $option->getId(), $index));
        }

        return $this;
    }

    public function removeOption(AbstractOption $option): self
    {
        if ($this->hasOption($option->getId())) {
            $this->apply(new AttributeOptionRemovedEvent($this->id, $option->getId()));
        }

        return $this;
    }

    public function hasOption(AggregateId $aggregateId): bool
    {
        foreach ($this->options as $option) {
            if ($aggregateId->isEqual($option)) {
                return true;
            }
        }

        return false;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOptionIndex(AggregateId $aggregateId): int
    {
        foreach ($this->options as $key => $option) {
            if ($aggregateId->isEqual($option)) {
                return $key;
            }
        }

        throw new \DomainException(sprintf('Option %s not exists in attribute', $aggregateId->getValue()));
    }

    protected function applyAttributeOptionAddedEvent(AttributeOptionAddedEvent $event): void
    {
        $this->insertOptionToArray($event->getIndex(), $event->getOptionId());
    }

    protected function applyAttributeOptionMovedEvent(AttributeOptionMovedEvent $event): void
    {
        $this->removeOptionFromArray($this->getOptionIndex($event->getOptionId()));
        $this->insertOptionToArray($event->getIndex(), $event->getOptionId());
    }

    protected function applyAttributeOptionRemovedEvent(AttributeOptionRemovedEvent $event): void
    {
        $this->removeOptionFromArray($this->getOptionIndex($event->getOptionId()));
    }

    private function insertOptionToArray(int $index, AggregateId $id): void
    {
        $start = array_slice($this->options, 0, $index);
        $end = array_slice($this->options, $index);

        $this->options = array_merge($start, [$id], $end);
    }

    private function removeOptionFromArray(int $index): void
    {
        $start = array_slice($this->options, 0, $index);
        $end = array_slice($this->options, $index+1);
        $this->options = array_merge($start, $end);
    }
}
