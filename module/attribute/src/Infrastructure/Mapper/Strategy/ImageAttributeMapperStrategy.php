<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper\Strategy;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Entity\Attribute\ImageAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;

class ImageAttributeMapperStrategy implements AttributeMapperStrategyInterface
{
    public function supported(AttributeType $type): bool
    {
        return $type->getValue() === ImageAttribute::TYPE;
    }

    public function map(array $values): ValueInterface
    {
        Assert::allRegex(array_keys($values), '/^[a-z]{2}_[A-Z]{2}$/');
        foreach ($values as $value) {
            if (null !== $value) {
                Assert::stringNotEmpty($value);
                Assert::uuid($value);
            }
        }

        return new TranslatableStringValue(new TranslatableString($values));
    }
}
