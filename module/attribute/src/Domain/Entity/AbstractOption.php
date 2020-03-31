<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Entity;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Attribute\Domain\Event\Option\OptionCreatedEvent;
use Ergonode\Attribute\Domain\Event\Option\OptionLabelChangedEvent;

/**
 *
 */
abstract class AbstractOption extends AbstractAggregateRoot
{
    /**
     * @var AggregateId
     */
    private AggregateId $id;

    /**
     * @var AttributeId
     */
    private AttributeId $attributeId;

    /**
     * @var OptionKey
     */
    private OptionKey $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $label;

    /**
     * @param AggregateId        $id
     * @param AttributeId        $attributeId
     * @param OptionKey          $code
     * @param TranslatableString $label
     *
     * @throws \Exception
     */
    public function __construct(AggregateId $id, AttributeId $attributeId, OptionKey $code, TranslatableString $label)
    {
        $this->apply(new OptionCreatedEvent($id, $attributeId, $code, $label));
    }

    /**
     * @param TranslatableString $label
     *
     * @throws \Exception
     */
    public function changeLabel(TranslatableString $label): void
    {
        if (!$label->isEqual($this->label)) {
            $this->apply(new OptionLabelChangedEvent($this->id, $this->label, $label));
        }
    }

    /**
     * @return AggregateId
     */
    public function getId(): AggregateId
    {
        return $this->id;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return OptionKey
     */
    public function getCode(): OptionKey
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    /**
     * @param OptionCreatedEvent $event
     */
    protected function applyOptionCreatedEvent(OptionCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->attributeId = $event->getAttributeId();
        $this->code = $event->getCode();
        $this->label = $event->getLabel();
    }

    /**
     * @param OptionLabelChangedEvent $event
     */
    protected function applyOptionLabelChangedEvent(OptionLabelChangedEvent $event): void
    {
        $this->label = $event->getTo();
    }
}
