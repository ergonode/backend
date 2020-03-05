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
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;

/**
 */
class AttributeColumnProvider
{
    /**
     * @var AttributeColumnStrategyInterface[]
     */
    private array $strategies;

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
     *
     * @return ColumnInterface
     */
    public function provide(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($attribute)) {
                return $strategy->create($attribute, $language);
            }
        }

        $columnKey = $attribute->getCode()->getValue();

        return new TextColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new TextFilter()
        );
    }
}
