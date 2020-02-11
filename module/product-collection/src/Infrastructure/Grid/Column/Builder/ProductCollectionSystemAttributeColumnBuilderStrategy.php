<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Infrastructure\Grid\Column\Builder;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\MultiSelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;
use Ergonode\ProductCollection\Domain\Entity\Attribute\ProductCollectionSystemAttribute;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;

/**
 */
class ProductCollectionSystemAttributeColumnBuilderStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var ProductCollectionQueryInterface
     */
    private ProductCollectionQueryInterface $query;

    /**
     * @param ProductCollectionQueryInterface $query
     */
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
        $categories = $this->query->getDictionary();

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
