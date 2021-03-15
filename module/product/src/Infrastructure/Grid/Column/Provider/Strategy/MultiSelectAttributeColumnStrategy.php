<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;
use Ergonode\Grid\Filter\Option\FilterOption;

class MultiSelectAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    private OptionQueryInterface $query;

    public function __construct(OptionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof MultiSelectAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $options = $this->query->getAll($attribute->getId());
        $result = [];
        foreach ($options as $option) {
            $label = $option['label'][$language->getCode()] ?? null;
            $result[] = new FilterOption(
                $option['id'],
                $option['code'],
                $label
            );
        }

        $columnKey = $attribute->getCode()->getValue();

        return new MultiSelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new MultiSelectFilter($result)
        );
    }
}
