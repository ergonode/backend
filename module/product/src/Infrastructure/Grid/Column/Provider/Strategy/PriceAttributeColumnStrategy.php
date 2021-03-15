<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\PriceAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\NumericFilter;

class PriceAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
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
        if (!$attribute instanceof PriceAttribute) {
            throw new \LogicException(
                sprintf(
                    'Expected an instance of %s. %s received.',
                    PriceAttribute::class,
                    get_debug_type($attribute)
                )
            );
        }
        $columnKey = $attribute->getCode()->getValue();
        $columnFilter = new NumericFilter();

        $column =  new NumericColumn($columnKey, $attribute->getLabel()->get($language), $columnFilter);
        $column->setSuffix($attribute->getCurrency()->getCode());

        return $column;
    }
}
