<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\MultiSelectFilter;
use Ergonode\Grid\Filter\Option\LabelFilterOption;
use Ergonode\Product\Domain\Entity\Attribute\ProductTypeSystemAttribute;
use Ergonode\Product\Domain\Entity\SimpleProduct;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Product\Domain\Entity\GroupingProduct;
use Ergonode\Product\Domain\Entity\VariableProduct;

class ProductTypeSystemAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof ProductTypeSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $options = [
            new LabelFilterOption(
                SimpleProduct::TYPE,
                $this->translator->trans(SimpleProduct::TYPE, [], 'product')
            ),
            new LabelFilterOption(
                GroupingProduct::TYPE,
                $this->translator->trans(GroupingProduct::TYPE, [], 'product')
            ),
            new LabelFilterOption(
                VariableProduct::TYPE,
                $this->translator->trans(VariableProduct::TYPE, [], 'product')
            ),

        ];

        $columnKey = $attribute->getCode()->getValue();

        return new SelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new MultiSelectFilter($options)
        );
    }
}
