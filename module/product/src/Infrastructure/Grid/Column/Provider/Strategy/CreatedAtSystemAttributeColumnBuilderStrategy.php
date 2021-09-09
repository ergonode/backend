<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\DateTimeColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\DateTimeFilter;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;

class CreatedAtSystemAttributeColumnBuilderStrategy implements AttributeColumnStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CreatedAtSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $columnKey = $attribute->getCode()->getValue();

        $column = new DateTimeColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new DateTimeFilter()
        );

        $column->setExtension('parameters', $attribute->getParameters());

        return $column;
    }
}
