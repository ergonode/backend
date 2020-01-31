<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Infrastructure\Grid\Column\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Category\Domain\Entity\Attribute\CategorySystemAttribute;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;

/**
 */
class CategorySystemAttributeColumnBuilderStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var CategoryQueryInterface
     */
    private CategoryQueryInterface $query;

    /**
     * @param CategoryQueryInterface $query
     */
    public function __construct(CategoryQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof CategorySystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $categories = $this->query->getDictionary($language);

        $options = [];
        foreach ($categories as $id => $option) {
            $options[$id] = $option;
        }

        $columnKey = $attribute->getCode()->getValue();

        return new MultiSelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new MultiSelectFilter($options)
        );
    }
}
