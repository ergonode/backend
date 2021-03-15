<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\NumericAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\NumericFilter;

class NumericAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof NumericAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $columnKey = $attribute->getCode()->getValue();
        $columnFilter = new NumericFilter();

        return new NumericColumn($columnKey, $attribute->getLabel()->get($language), $columnFilter);
    }
}
