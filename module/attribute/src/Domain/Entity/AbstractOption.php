<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent;
use Ergonode\Attribute\Domain\Event\Option\OptionLabelChangedEvent;
use Ergonode\Attribute\Domain\Event\Option\OptionCodeChangedEvent;

abstract class AbstractOption extends AbstractAggregateRoot
{
    private AggregateId $id;

    private OptionKey $code;

    private TranslatableString $label;

    /**
     * @throws \Exception
     */
    public function __construct(AggregateId $id, OptionKey $code, TranslatableString $label)
    {
        $this->apply(new OptionCreatedEvent($id, $code, $label));
    }

    /**
     * @throws \Exception
     */
    public function changeLabel(TranslatableString $label): void
    {
        if (!$label->isEqual($this->label)) {
            $this->apply(new OptionLabelChangedEvent($this->id, $label));
        }
    }

    /**
     * @throws \Exception
     */
    public function changeCode(OptionKey $code): void
    {
        if (!$code->isEqual($this->code)) {
            $this->apply(new OptionCodeChangedEvent($this->id, $code));
        }
    }

    public function getId(): AggregateId
    {
        return $this->id;
    }

    public function getCode(): OptionKey
    {
        return $this->code;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    protected function applyOptionCreatedEvent(OptionCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->code = $event->getCode();
        $this->label = $event->getLabel();
    }

    protected function applyOptionLabelChangedEvent(OptionLabelChangedEvent $event): void
    {
        $this->label = $event->getTo();
    }

    protected function applyOptionCodeChangedEvent(OptionCodeChangedEvent $event): void
    {
        $this->code = $event->getCode();
    }
}
