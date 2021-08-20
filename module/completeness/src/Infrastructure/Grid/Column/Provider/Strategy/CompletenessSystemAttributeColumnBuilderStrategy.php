<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Completeness\Domain\Entity\Attribute\CompletenessSystemAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\NumericColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;

class CompletenessSystemAttributeColumnBuilderStrategy implements AttributeColumnStrategyInterface
{

    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CompletenessSystemAttribute;
    }

    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $column = new NumericColumn(
            $attribute->getCode()->getValue(),
            $attribute->getLabel()->get($language),
            new TextFilter()
        );

        $column->setSuffix('%');

        return $column;
    }
}
