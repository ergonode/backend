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
use Ergonode\Attribute\Domain\Query\OptionQueryInterface;

/**
 */
class SelectAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var OptionQueryInterface
     */
    private OptionQueryInterface $query;

    /**
     * @param OptionQueryInterface $query
     */
    public function __construct(OptionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof SelectAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $options = $this->query->getList($attribute->getId(), $language);

        $columnKey = $attribute->getCode()->getValue();

        return new SelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new SelectFilter($options)
        );
    }
}
