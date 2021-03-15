<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\UnitAttribute;
use Ergonode\Core\Domain\Entity\Unit;
use Ergonode\Core\Domain\Repository\UnitRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\NumericFilter;

class UnitAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    private UnitRepositoryInterface $unitRepository;

    public function __construct(UnitRepositoryInterface $unitRepository)
    {
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
        $columnKey = $attribute->getCode()->getValue();
        $columnFilter = new NumericFilter();
        /** @var Unit $unit */
        $unit = $this->unitRepository->load($attribute->getUnitId());

        $column = new NumericColumn($columnKey, $attribute->getLabel()->get($language), $columnFilter);
        $column->setSuffix($unit->getSymbol());

        return $column;
    }
}
