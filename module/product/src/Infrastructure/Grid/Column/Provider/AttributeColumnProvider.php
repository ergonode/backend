<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\TextColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\TextFilter;
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
     * @param array             $filter
     *
     * @return ColumnInterface
     */
    public function provide(AbstractAttribute $attribute, Language $language, array $filter = []): ColumnInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->isSupported($attribute)) {
                return $strategy->create($attribute, $language, $filter);
            }
        }

        $value = null;
        if (!empty($filter)) {
            $value = reset($filter);
        }

        return new TextColumn($attribute->getCode()->getValue(), $attribute->getLabel()->get($language), new TextFilter($value));
    }
}
