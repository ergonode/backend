<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AttributeColumnStrategyInterface;

/**
 */
class TemplateSystemAttributeColumnStrategy implements AttributeColumnStrategyInterface
{
    /**
     * @var TemplateQueryInterface
     */
    private TemplateQueryInterface $query;

    /**
     * @param TemplateQueryInterface $query
     */
    public function __construct(TemplateQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute instanceof TemplateSystemAttribute;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language): ColumnInterface
    {
        $options = $this->query->getDictionary($language);

        $columnKey = $attribute->getCode()->getValue();

        return new SelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new SelectFilter($options)
        );
    }
}
