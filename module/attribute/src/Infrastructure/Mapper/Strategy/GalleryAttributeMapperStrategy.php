<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Mapper\Strategy;

use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\GalleryAttribute;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Webmozart\Assert\Assert;
use Ergonode\Value\Domain\ValueObject\StringCollectionValue;

class GalleryAttributeMapperStrategy implements AttributeMapperStrategyInterface
{
    public function supported(AttributeType $type): bool
    {
        return $type->getValue() === GalleryAttribute::TYPE;
    }

    public function map(array $values): ValueInterface
    {
        Assert::allRegex(array_keys($values), '/^[a-z]{2}_[A-Z]{2}$/');
        Assert::allIsArray($values);
        foreach ($values as $language => $value) {
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
