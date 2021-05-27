<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper\Strategy;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Entity\Attribute\DateAttribute;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;

class DateAttributeMapperStrategy implements ContextAwareAttributeMapperStrategyInterface
{
    public function supported(AttributeType $type): bool
    {
        return $type->getValue() === DateAttribute::TYPE;
    }

    public function map(array $values, ?ProductId $productId = null): ValueInterface
    {
        Assert::allRegex(array_keys($values), '/^[a-z]{2}_[A-Z]{2}$/');
        foreach ($values as $value) {
            if (null !== $value) {
                Assert::stringNotEmpty($value);
                if ($value && !strtotime($value)) {
                    throw new \InvalidArgumentException(sprintf('Value "%s" is\'t valid date format', $value));
                }
            }
        }

        return new TranslatableStringValue(new TranslatableString($values));
    }
}
