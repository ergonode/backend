<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Mapper\Strategy;

use Ergonode\Attribute\Infrastructure\Mapper\Strategy\ContextAwareAttributeMapperStrategyInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;

class ProductRelationAttributeMapperStrategy implements ContextAwareAttributeMapperStrategyInterface
{
    public function supported(AttributeType $type): bool
    {
        return $type->getValue() === ProductRelationAttribute::TYPE;
    }

    public function map(array $values, ?ProductId $productId = null): ValueInterface
    {
        Assert::allRegex(array_keys($values), '/^[a-z]{2}_[A-Z]{2}$/');
        Assert::notNull($productId);
        $mappedValues = [];
        foreach ($values as $language => $value) {
            if (null !== $value && !is_array($value)) {
                $value = explode(',', (string) $value);
            }

            if (is_array($value) && !empty($value)) {
                Assert::allUuid($value);
                $value = $this->filter($value, $productId);

                if (empty($value)) {
                    continue;
                }
                $mappedValues[$language] = implode(',', $value);
            } else {
                $mappedValues[$language] = null;
            }
        }

        return new StringCollectionValue($mappedValues);
    }

    private function filter(array $value, ProductId $productId): ?array
    {
        $result = [];
        foreach ($value as $item) {
            if ($item !== $productId->getValue()) {
                $result[] = $item;
            }
        }

        return !empty($result) ? $result : null;
    }
}
