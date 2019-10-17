<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\AttributeDate\Domain\Entity\DateAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
class DateAttributeColumnStrategy extends AbstractLanguageColumnStrategy
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute->getType() === DateAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        $columnKey = $attribute->getCode()->getValue();
        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);

        return new DateColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new TextFilter($filter->get($filterKey))
        );
    }
}
