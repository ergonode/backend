<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeUnit\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\AttributeUnit\Domain\ValueObject\Unit;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UnitAttribute extends AbstractAttribute
{
    public const TYPE = 'UNIT';
    public const CODE = 'code';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param Unit               $unit
     */
    public function __construct(AttributeId $id, AttributeCode $code, TranslatableString $label, TranslatableString $hint, TranslatableString $placeholder, bool $multilingual, Unit $unit)
    {
        parent::__construct($id, $code, $label, $hint, $placeholder, $multilingual, [self::CODE => $unit->getCode()]);
    }

    /**
     * @JMS\VirtualProperty();
     * @JMS\SerializedName("type")
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return Unit
     */
    public function getUnit(): Unit
    {
        return new Unit($this->getParameter(self::CODE));
    }

    /**
     * @param Unit $new
     */
    public function changeUnit(Unit $new): void
    {
        if ($this->getUnit()->getCode() !== $new->getCode()) {
            $this->apply(new AttributeParameterChangeEvent(self::CODE, $this->getUnit()->getCode(), $new->getCode()));
        }
    }
}
