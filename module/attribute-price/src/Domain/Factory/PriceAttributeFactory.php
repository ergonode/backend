<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributePrice\Domain\Factory;

use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Money\Currency;

/**
 */
class PriceAttributeFactory implements AttributeFactoryInterface
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
     * @param CreateAttributeCommand $command
     *
     * @return AbstractAttribute
     */
    public function create(CreateAttributeCommand $command): AbstractAttribute
    {
        if (!$command->hasParameter('currency')) {
            throw new \InvalidArgumentException('No required format parameter');
        }

        $currency = new Currency($command->getParameter('currency'));

        return new PriceAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $command->isMultilingual(),
            $currency
        );
    }
}
