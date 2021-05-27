<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper\Strategy;

use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\FileAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;

class FileAttributeMapperStrategy implements ContextAwareAttributeMapperStrategyInterface
{
    public function supported(AttributeType $type): bool
    {
        return $type->getValue() === FileAttribute::TYPE;
    }

    public function map(array $values, ?ProductId $productId = null): ValueInterface
    {
        Assert::allRegex(array_keys($values), '/^[a-z]{2}_[A-Z]{2}$/');

        foreach ($values as $language => $value) {
            if (null !== $value && !is_array($value)) {
                $value = explode(',', (string) $value);
            }

            if (is_array($value) && !empty($value)) {
                Assert::allUuid($value);
                $values[$language] = implode(',', $value);
            } else {
                $values[$language] = null;
            }
        }

        return new StringCollectionValue($values);
    }
}
