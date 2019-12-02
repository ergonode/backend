<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Infrastructure\Grid\Column\Provider\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Attribute\Domain\Entity\Attribute\SelectAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;
use Ergonode\Designer\Domain\Query\TemplateQueryInterface;
use Ergonode\Grid\Column\SelectColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Request\FilterCollection;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AbstractLanguageColumnStrategy;
use vendor\project\StatusTest;

/**
 */
class TemplateSystemAttributeColumnStrategy extends AbstractLanguageColumnStrategy
{
    /**
     * @var TemplateQueryInterface
     */
    private $query;

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
        return $attribute->getType() === TemplateSystemAttribute::TYPE;
    }

    /**
     * {@inheritDoc}
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        $options = $this->query->getDictionary($language);

        $columnKey = $attribute->getCode()->getValue();

        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);

        return new SelectColumn(
            $columnKey,
            $attribute->getLabel()->get($language),
            new SelectFilter($options, $filter->get($filterKey))
        );
    }
}
