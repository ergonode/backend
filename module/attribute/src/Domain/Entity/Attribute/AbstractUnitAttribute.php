<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Entity\Attribute;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

abstract class AbstractUnitAttribute extends AbstractAttribute
{
    public const TYPE = 'UNIT';
    public const UNIT = 'unit';

    /**
     * @throws \Exception
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        UnitId $unitId
    ) {
        parent::__construct(
            $id,
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            [self::UNIT => $unitId->getValue()]
        );
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getUnitId(): UnitId
    {
        return new UnitId($this->getParameter(self::UNIT));
    }

    /**
     * @throws \Exception
     */
    public function changeUnit(UnitId $new): void
    {
        if ($this->getUnitId()->getValue() !== $new->getValue()) {
            $event = new AttributeStringParameterChangeEvent(
                $this->id,
                self::UNIT,
                $new->getValue()
            );
            $this->apply($event);
        }
    }
}
