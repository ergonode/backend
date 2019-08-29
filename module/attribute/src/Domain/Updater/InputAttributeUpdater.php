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
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

/**
 */
class InputAttributeUpdater implements AttributeUpdaterInterface
{
    private const SUPPORTED = [
        TextAttribute::TYPE,
        TextareaAttribute::TYPE,
        NumericAttribute::TYPE,
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
     * @param AbstractAttribute      $attribute
     * @param UpdateAttributeCommand $command
     *
     * @return AbstractAttribute
     */
    public function update(AbstractAttribute $attribute, UpdateAttributeCommand $command): AbstractAttribute
    {
        return $attribute;
    }
}
