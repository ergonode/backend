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
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

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

        foreach ($command->getOptions() as $key => $option) {
            $key = new OptionKey((string) $key);

            if (null === $option) {
                if ($attribute->isMultilingual()) {
                    $option = new MultilingualOption(new TranslatableString());
                } else {
                    $option = new StringOption('');
                }
            }

            if ($attribute->hasOption($key)) {
                $attribute->changeOption($key, $option);
            } else {
                $attribute->addOption($key, $option);
            }
        }

        foreach ($attribute->getOptions() as $key => $option) {
            $key = new OptionKey((string) $key);
            if (!array_key_exists($key->getValue(), $command->getOptions())) {
                $attribute->removeOption($key);
            }
        }

        return $attribute;
    }
}
