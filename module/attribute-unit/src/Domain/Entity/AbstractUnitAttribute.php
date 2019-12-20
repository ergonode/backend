<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeUnit\Domain\Entity;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\AttributeUnit\Domain\ValueObject\Unit;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractUnitAttribute extends AbstractAttribute
{
    public const TYPE = 'UNIT';
    public const CODE = 'code';

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param Unit               $unit
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        Unit $unit
    ) {
        parent::__construct($id, $code, $label, $hint, $placeholder, false, [self::CODE => $unit->getCode()]);
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
     *
     * @throws \Exception
     */
    public function changeUnit(Unit $new): void
    {
        if ($this->getUnit()->getCode() !== $new->getCode()) {
            $this->apply(new AttributeParameterChangeEvent($this->id, self::CODE, $this->getUnit()->getCode(), $new->getCode()));
        }
    }
}
