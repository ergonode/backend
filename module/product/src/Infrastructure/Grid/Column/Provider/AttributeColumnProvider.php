<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;
use Ergonode\Grid\Request\FilterCollection;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;

/**
 */
class AttributeColumnProvider
{
    /**
     * @var AttributeColumnStrategyInterface[]
     */
    private $strategies;

    /**
     * @param AttributeColumnStrategyInterface ...$strategies
     */
    public function __construct(AttributeColumnStrategyInterface ...$strategies)
    {
        $this->strategies = $strategies;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     * @param FilterCollection  $filter
     *
     * @return ColumnInterface
     */
    public function provide(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($attribute)) {
                return $strategy->create($attribute, $language, $filter);
            }
        }

        $columnKey = $attribute->getCode()->getValue();
        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);

        return new TextColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new TextFilter($filter->get($filterKey))
        );
    }

    /**
     * @param string           $columnKey
     * @param string           $languageCode
     * @param FilterCollection $filter
     *
     * @return string
     */
    protected function getFilterKey(string $columnKey, string $languageCode, FilterCollection $filter): string
    {
        $filterKey = $columnKey.':'.$languageCode;
        if (!$filter->has($filterKey)) {
            $filterKey = $columnKey;
        }

        return $filterKey;
    }
}
