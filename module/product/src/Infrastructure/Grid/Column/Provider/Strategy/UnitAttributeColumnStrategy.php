<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\RangeFilter;

/**
 */
class UnitAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var AttributeQueryInterface
     */
    private AttributeQueryInterface $attributeQuery;

    /**
     * @var UnitRepositoryInterface
     */
    private UnitRepositoryInterface $unitRepository;

    /**
     * @param AttributeQueryInterface $attributeQuery
     * @param UnitRepositoryInterface $unitRepository
     */
    public function __construct(AttributeQueryInterface $attributeQuery, UnitRepositoryInterface $unitRepository)
    {
        $this->attributeQuery = $attributeQuery;
        $this->unitRepository = $unitRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof UnitAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        /** @var UnitAttribute $attribute */
        $range = $this->attributeQuery->getAttributeValueRange($attribute->getId());
        $columnKey = $attribute->getCode()->getValue();
        $columnFilter = new RangeFilter($range);
        /** @var Unit $unit */
        $unit = $this->unitRepository->load($attribute->getUnitId());

        $column = new NumericColumn($columnKey, $attribute->getLabel()->get($language), $columnFilter);
        $column->setSuffix($unit->getSymbol());

        return $column;
    }
}
