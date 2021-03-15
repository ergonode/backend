<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\DefaultLabelSystemAttribute;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;

class DefaultLabelSystemAttributeColumnBuilderStrategy implements AttributeColumnStrategyInterface
{
    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof DefaultLabelSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $columnKey = $attribute->getCode()->getValue();

        return new TextColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new TextFilter()
        );
    }
}
