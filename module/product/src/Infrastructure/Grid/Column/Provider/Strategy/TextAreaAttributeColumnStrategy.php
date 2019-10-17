<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\TextareaAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TextAreaColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
class TextAreaAttributeColumnStrategy extends AbstractLanguageColumnStrategy
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
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        $columnKey = $attribute->getCode()->getValue();

        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);

        return new TextAreaColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new TextFilter($filter->get($filterKey))
        );
    }
}
