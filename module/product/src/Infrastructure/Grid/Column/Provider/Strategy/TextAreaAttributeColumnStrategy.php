<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TextAreaColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;

/**
 */
class TextAreaAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute->getType() === TextareaAttribute::TYPE;
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
        return new TextAreaColumn($attribute->getCode()->getValue(), $attribute->getLabel()->get($language), new TextFilter($filter[0]));
    }
}
