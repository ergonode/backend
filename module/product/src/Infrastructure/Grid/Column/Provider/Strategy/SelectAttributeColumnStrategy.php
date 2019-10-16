<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Request\FilterCollection;

/**
 */
class SelectAttributeColumnStrategy extends AbstractLanguageColumnStrategy
{
    /**
     * @param AbstractAttribute $attribute
     *
     * @return bool
     */
    public function isSupported(AbstractAttribute $attribute): bool
    {
        return $attribute->getType() === SelectAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        $options = [];
        foreach ($attribute->getOptions() as $id => $option) {
            if ($option instanceof StringOption) {
                $options[$id] = $option->getValue();
            } elseif ($option instanceof MultilingualOption) {
                $options[$id] = $option->getValue()->get($language);
            }
        }

        $columnKey = $attribute->getCode()->getValue();

        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);

        return new SelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new SelectFilter($options, $filter->get($filterKey))
        );
    }
}
