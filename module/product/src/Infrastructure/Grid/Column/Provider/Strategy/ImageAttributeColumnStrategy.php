<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\AttributeImage\Domain\Entity\ImageAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\ImageColumn;
use Ergonode\Grid\ColumnInterface;

/**
 */
class ImageAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute->getType() === ImageAttribute::TYPE;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     * @param array             $filter
     *
     * @return ColumnInterface
     */
    public function create(AbstractAttribute $attribute, Language $language, array $filter = []): ColumnInterface
    {
        return new ImageColumn($attribute->getCode()->getValue());
    }
}
