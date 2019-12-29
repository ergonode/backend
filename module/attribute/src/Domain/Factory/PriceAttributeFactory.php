<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Factory;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Money\Currency;

/**
 */
class PriceAttributeFactory implements AttributeFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AttributeType $type): bool
    {
        return PriceAttribute::TYPE === $type->getValue();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
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
            $currency
        );
    }
}
