<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Updater;

use Ergonode\Attribute\Domain\AttributeUpdaterInterface;
use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

/**
 */
class UnitAttributeUpdater implements AttributeUpdaterInterface
{
    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function isSupported(AttributeType $type): bool
    {
        return UnitAttribute::TYPE === $type->getValue();
    }

    /**
     * @param AbstractAttribute|UnitAttribute $attribute
     * @param UpdateAttributeCommand          $command
     *
     * @return AbstractAttribute
     */
    public function update(AbstractAttribute $attribute, UpdateAttributeCommand $command): AbstractAttribute
    {
        if (!$command->hasParameter('unit')) {
            throw new \InvalidArgumentException('No required unit parameter');
        }

        $unit = new UnitId($command->getParameter('unit'));

        $attribute->changeUnit($unit);

        return $attribute;
    }
}
