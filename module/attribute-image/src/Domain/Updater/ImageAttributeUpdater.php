<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeImage\Domain\Updater;

use Ergonode\Attribute\Domain\AttributeUpdaterInterface;
use Ergonode\Attribute\Domain\Command\UpdateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributeImage\Domain\Entity\ImageAttribute;
use Ergonode\AttributeImage\Domain\ValueObject\ImageFormat;

/**
 */
class ImageAttributeUpdater implements AttributeUpdaterInterface
{
    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function isSupported(AttributeType $type): bool
    {
        return ImageAttribute::TYPE === $type->getValue();
    }

    /**
     * @param AbstractAttribute|ImageAttribute $attribute
     * @param UpdateAttributeCommand           $command
     *
     * @return AbstractAttribute
     */
    public function update(AbstractAttribute $attribute, UpdateAttributeCommand $command): AbstractAttribute
    {
        $formats = [];
        if ($command->hasParameter('formats')) {
            foreach ($command->getParameter('formats') as $format) {
                $formats[] = new ImageFormat($format);
            }
            $attribute->changeFormats($formats);
        }

        return $attribute;
    }
}
