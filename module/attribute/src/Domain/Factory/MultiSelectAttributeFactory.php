<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Factory;

use Ergonode\Attribute\Domain\AttributeFactoryInterface;
use Ergonode\Attribute\Domain\Command\CreateAttributeCommand;
use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class MultiSelectAttributeFactory implements AttributeFactoryInterface
{
    /**
     * @param AttributeType $type
     *
     * @return bool
     */
    public function isSupported(AttributeType $type): bool
    {
        return MultiSelectAttribute::TYPE === $type->getValue();
    }

    /**
     * @param CreateAttributeCommand $command
     *
     * @return AbstractAttribute
     */
    public function create(CreateAttributeCommand $command): AbstractAttribute
    {
        $attribute = new MultiSelectAttribute(
            $command->getId(),
            $command->getCode(),
            $command->getLabel(),
            $command->getHint(),
            $command->getPlaceholder(),
            $command->isMultilingual()
        );

        foreach ($command->getOptions() as $key => $option) {
            if (null === $option) {
                if ($command->isMultilingual()) {
                    $option = new MultilingualOption(new TranslatableString());
                } else {
                    $option = new StringOption('');
                }
            }
            $attribute->addOption(new OptionKey($key), $option);
        }

        return $attribute;
    }
}
