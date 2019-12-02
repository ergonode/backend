<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Grid\Column\Strategy;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\LabelColumn;
use Ergonode\Grid\ColumnInterface;
use Ergonode\Grid\Filter\SelectFilter;
use Ergonode\Grid\Request\FilterCollection;
use Ergonode\Product\Infrastructure\Grid\Column\Provider\Strategy\AbstractLanguageColumnStrategy;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;

/**
 */
class StatusAttributeColumnStrategy extends AbstractLanguageColumnStrategy
{
    /**
     * @var StatusQueryInterface
     */
    private $statusQuery;

    /**
     * @param StatusQueryInterface $statusQuery
     */
    public function __construct(StatusQueryInterface $statusQuery)
    {
        $this->statusQuery = $statusQuery;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(AbstractAttribute $attribute): bool
    {
        return $attribute->getType() === StatusSystemAttribute::TYPE;
    }

    /**
     * @param AbstractAttribute $attribute
     * @param Language          $language
     * @param FilterCollection  $filter
     *
     * @return ColumnInterface
     *
     * @throws \Exception
     */
    public function create(AbstractAttribute $attribute, Language $language, FilterCollection $filter): ColumnInterface
    {
        $columnKey = $attribute->getCode()->getValue();
        $filterKey = $this->getFilterKey($columnKey, $language->getCode(), $filter);
        $statuses = $this->statusQuery->getAllStatuses($language);

        $statusCodes = [];
        foreach ($statuses as $code => $status) {
            $statusCodes[$code] = $status['name'];
        }

        return new LabelColumn(
            StatusSystemAttribute::CODE,
            $attribute->getLabel()->get($language),
            $statuses,
            new SelectFilter($statusCodes, $filter->get($filterKey))
        );
    }
}
