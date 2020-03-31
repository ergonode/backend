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
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

/**
 */
class OptionAttributeUpdater implements AttributeUpdaterInterface
{
    private const SUPPORTED = [
        SelectAttribute::TYPE,
        MultiSelectAttribute::TYPE,
    ];

    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function isSupported(AttributeType $type): bool
    {
        return in_array($type->getValue(), self::SUPPORTED, true);
    }

    /**
     * @param AbstractAttribute|MultiSelectAttribute $attribute
     * @param UpdateAttributeCommand                 $command
     *
     * @return AbstractAttribute
     */
    public function update(AbstractAttribute $attribute, UpdateAttributeCommand $command): AbstractAttribute
    {
        return $attribute;
    }
}
