<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\AttributeOptionAddedEvent;
use Ergonode\Attribute\Domain\Event\AttributeOptionChangedEvent;
use Ergonode\Attribute\Domain\Event\AttributeOptionRemovedEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
abstract class AbstractOptionAttribute extends AbstractAttribute
{
    public const OPTIONS = 'options';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual
    ) {
        parent::__construct($id, $code, $label, $hint, $placeholder, $multilingual, [self::OPTIONS => []]);
    }

    /**
     * @param OptionKey $key
     *
     * @return bool
     */
    public function hasOption(OptionKey $key): bool
    {
        $options = $this->getParameter(self::OPTIONS);

        return isset($options[$key->getValue()]);
    }

    /**
     * @return OptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->getParameter(self::OPTIONS);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->getParameter(self::OPTIONS));
    }

    /**
     * @param OptionKey       $key
     * @param OptionInterface $option
     *
     * @throws \Exception
     */
    public function addOption(OptionKey $key, OptionInterface $option): void
    {
        if ($this->hasOption($key)) {
            throw new \InvalidArgumentException(sprintf('option %s already exists', $key));
        }

        if ($option->isMultilingual() !== $this->isMultilingual()) {
            throw new \InvalidArgumentException(sprintf(
                'option %s must have same multilingual value, got %s',
                $key,
                get_class($option)
            ));
        }

        $this->apply(new AttributeOptionAddedEvent($this->id, $key, $option));
    }

    /**
     * @param OptionKey $key
     *
     * @return OptionInterface
     */
    public function getOption(OptionKey $key): OptionInterface
    {
        if (!$this->hasOption($key)) {
            throw new \InvalidArgumentException(sprintf('option value %s already exists', $key));
        }

        return $this->getParameter(self::OPTIONS)[$key->getValue()];
    }

    /**
     * @param OptionKey       $key
     * @param OptionInterface $to
     *
     * @throws \Exception
     */
    public function changeOption(OptionKey $key, OptionInterface $to): void
    {
        $from = $this->getOption($key);

        if ($to->isMultilingual() !== $this->isMultilingual()) {
            throw new \InvalidArgumentException(sprintf('option %s must have same multilingual value', $key));
        }

        if (!$from->equal($to)) {
            $this->apply(new AttributeOptionChangedEvent($this->id, $key, $from, $to));
        }
    }

    /**
     * @param OptionKey $key
     *
     * @throws \Exception
     */
    public function removeOption(OptionKey $key): void
    {
        if (!$this->hasOption($key)) {
            throw new \InvalidArgumentException(sprintf('option value %s not exists', $key));
        }

        $this->apply(new AttributeOptionRemovedEvent($this->id, $key));
    }

    /**
     * @param AttributeOptionAddedEvent $event
     */
    protected function applyAttributeOptionAddedEvent(AttributeOptionAddedEvent $event): void
    {
        $this->parameters[self::OPTIONS][$event->getKey()->getValue()] = $event->getOption();
    }

    /**
     * @param AttributeOptionChangedEvent $event
     */
    protected function applyAttributeOptionChangedEvent(AttributeOptionChangedEvent $event): void
    {
        $this->parameters[self::OPTIONS][$event->getKey()->getValue()] = $event->getTo();
    }

    /**
     * @param AttributeOptionRemovedEvent $event
     */
    protected function applyAttributeOptionRemovedEvent(AttributeOptionRemovedEvent $event): void
    {
        unset($this->parameters[self::OPTIONS][$event->getKey()->getValue()]);
    }
}
