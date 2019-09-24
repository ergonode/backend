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
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

/**
 */
class TextareaAttributeFactory implements AttributeFactoryInterface
{
    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function isSupported(AttributeType $type): bool
    {
        return TextareaAttribute::TYPE === $type->getValue();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Exception
     */
    public function create(CreateAttributeCommand $command): AbstractAttribute
    {
        return new TextareaAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $command->isMultilingual(),
            [],
            $command->isSystem()
        );
    }
}
