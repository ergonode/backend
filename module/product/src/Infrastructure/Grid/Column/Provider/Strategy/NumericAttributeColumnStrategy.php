<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;
use Ergonode\AttributeUnit\Domain\Entity\UnitAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\RangeFilter;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
class NumericAttributeColumnStrategy extends AbstractLanguageColumnStrategy
{
    /**
     * @var AttributeQueryInterface
     */
    private $query;

    /**
     * @param AttributeQueryInterface $query
     */
    public function __construct(AttributeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return in_array($attribute->getType(), [NumericAttribute::TYPE, PriceAttribute::TYPE, UnitAttribute::TYPE], true);
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        $range = $this->query->getAttributeValueRange($attribute->getId());

        $columnKey = $attribute->getCode()->getValue();
        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);

        $columnFilter = new RangeFilter($range, $filter->get($filterKey));

        return new NumericColumn($columnKey, $attribute->getLabel()->get($language), $columnFilter);
    }
}
