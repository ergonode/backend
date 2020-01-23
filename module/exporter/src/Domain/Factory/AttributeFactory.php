<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\Attribute;
use Ergonode\Exporter\Domain\Entity\Attribute\TextAttribute;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class AttributeFactory
{

    /**
     * @param string              $key
     * @param ValueInterface|null $attribute
     *
     * @return Attribute
     */
    public static function create(string $key, ?ValueInterface $attribute = null): Attribute
    {
        if (null === $attribute) {
            return new Attribute($key, '');
        }
        if ($attribute instanceof StringValue) {
            return new TextAttribute($key, $attribute->getValue());
        }

        return new TextAttribute($key, $attribute->getValue());
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public static function createList(array $attributes): array
    {
        $result = [];
        foreach ($attributes as $key => $attribute) {
            $result[$key] = self::create($key, $attribute);
        }

        return $result;
    }
}
