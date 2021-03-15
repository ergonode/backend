<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ProductCollection\Infrastructure\Grid\Column\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;
use Ergonode\ProductCollection\Domain\Entity\Attribute\ProductCollectionSystemAttribute;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Ergonode\Grid\Filter\Option\FilterOption;

class ProductCollectionSystemAttributeColumnBuilderStrategy implements AttributeColumnStrategyInterface
{
    private ProductCollectionQueryInterface $query;

    public function __construct(ProductCollectionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductCollectionSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $categories = $this->query->getOptions($language);

        $options = [];
        foreach ($categories as $category) {
            $options[] = new FilterOption($category['id'], $category['code'], $category['name']);
        }

        $columnKey = $attribute->getCode()->getValue();

        return new MultiSelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new MultiSelectFilter($options)
        );
    }
}
