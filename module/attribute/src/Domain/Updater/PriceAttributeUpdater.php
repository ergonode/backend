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
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Money\Currency;

/**
 */
class PriceAttributeUpdater implements AttributeUpdaterInterface
{
    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function isSupported(AttributeType $type): bool
    {
        return PriceAttribute::TYPE === $type->getValue();
    }

    /**
     * @param AbstractAttribute|PriceAttribute $attribute
     * @param UpdateAttributeCommand           $command
     *
     * @return AbstractAttribute
     */
    public function update(AbstractAttribute $attribute, UpdateAttributeCommand $command): AbstractAttribute
    {
        if ($command->hasParameter('currency')) {
            $currency = new Currency($command->getParameter('currency'));

            $attribute->changeCurrency($currency);
        }

        return $attribute;
    }
}
