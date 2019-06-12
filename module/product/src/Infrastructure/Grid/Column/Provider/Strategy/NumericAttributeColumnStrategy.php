<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractOptionAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\AttributePrice\Domain\Entity\PriceAttribute;
use Ergonode\AttributeUnit\Domain\Entity\UnitAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\RangeFilter;

/**
 */
class NumericAttributeColumnStrategy implements AttributeColumnStrategyInterface
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
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return in_array($attribute->getType(), [NumericAttribute::TYPE, PriceAttribute::TYPE, UnitAttribute::TYPE], true);
    }

    /**
     * @param AbstractAttribute|AbstractOptionAttribute $attribute
     * @param Language                                  $language
     * @param array                                     $filter
     *
     * @return ColumnInterface
     *
     */
    public function create(AbstractAttribute $attribute, Language $language, array $filter = []): ColumnInterface
    {
        $range = $this->query->getAttributeValueRange($attribute->getId());

        $filter = new RangeFilter((int) $range['min'], (int) $range['max'], reset($filter));

        return new NumericColumn($attribute->getCode()->getValue(), $attribute->getLabel()->get($language), $filter);
    }
}
