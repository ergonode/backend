<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\RangeFilter;

/**
 */
class PriceAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $query;

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
        return $attribute instanceof PriceAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        /** @var PriceAttribute $attribute */
        $range = $this->query->getAttributeValueRange($attribute->getId());
        $columnKey = $attribute->getCode()->getValue();
        $columnFilter = new RangeFilter($range);

        $column =  new NumericColumn($columnKey, $attribute->getLabel()->get($language), $columnFilter);
        $column->setSuffix($attribute->getCurrency()->getCode());

        return $column;
    }
}
