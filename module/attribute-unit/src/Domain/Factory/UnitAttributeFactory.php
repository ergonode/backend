<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeUnit\Domain\Factory;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\AttributeUnit\Domain\Entity\UnitAttribute;
use Ergonode\AttributeUnit\Domain\ValueObject\Unit;

/**
 */
class UnitAttributeFactory implements AttributeFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AttributeType $type): bool
    {
        return UnitAttribute::TYPE === $type->getValue();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function create(CreateAttributeCommand $command): AbstractAttribute
    {
        if (!$command->hasParameter('unit')) {
            throw new \InvalidArgumentException('No required unit parameter');
        }

        $unit = new Unit($command->getParameter('unit'));

        return new UnitAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $unit
        );
    }
}
